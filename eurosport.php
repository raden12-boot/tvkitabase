<?php
// 1. Konfigurasi Rahasia
$authorized_ua = "";
// URL asli yang di-encode ke Base64
$encoded_url = base64_encode("https://tvkitasecret.netlify.app/espn1.html");

// 2. Ambil User-Agent pengunjung
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// 3. Logika Pengecekan Server-Side
$is_allowed = (strpos($user_agent, $authorized_ua) !== false);

// Jika tidak cocok, langsung hentikan proses
if (!$is_allowed) {
    header('HTTP/1.0 403 Forbidden');
    exit("<h1 style='color:red;text-align:center;'>403 Forbidden</h1><p style='text-align:center;'>Akses ditolak oleh server.</p>");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Live Stream Player</title>
    <style>
        body, html { 
            margin: 0; 
            padding: 0; 
            height: 100%; 
            width: 100%;
            overflow: hidden; 
            background: #000; 
        }
        .player-container { 
            position: relative; 
            width: 100%; 
            height: 100vh; 
        }
        iframe {
            position: absolute;
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>

<div class="player-container">
    <iframe 
        id="streamFrame"
        src="about:blank"
        /* Sandbox dihapus agar semua fitur player (DRM & Script) jalan normal */
        allow="autoplay; encrypted-media; fullscreen; picture-in-picture" 
        allowfullscreen>
    </iframe>
</div>

<script>
    (function() {
        // Mengambil string base64 dari PHP
        const secretBase64 = "<?php echo $encoded_url; ?>";
        const frame = document.getElementById('streamFrame');
        
        // Dekode URL dan masukkan ke iframe
        try {
            frame.src = atob(secretBase64);
        } catch (e) {
            console.error("Gagal memuat stream.");
        }

        // Proteksi: Klik kanan dilarang
        document.addEventListener('contextmenu', event => event.preventDefault());
        
        // Tambahan: Cegah Inspect Element standar (F12 / Ctrl+Shift+I)
        document.onkeydown = function(e) {
            if (e.keyCode == 123 || (e.ctrlKey && e.shiftKey && (e.keyCode == 'I'.charCodeAt(0) || e.keyCode == 'J'.charCodeAt(0) || e.keyCode == 'C'.charCodeAt(0)))) {
                return false;
            }
        };
    })();
</script>

</body>
</html>
