<!DOCTYPE html>
<html>
<head>
    <title>Reconhecimento Facial - Câmera</title>
    <style>
        video {
            width: 50%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Reconhecimento Facial - Câmera</h1>
    <video id="video" autoplay></video>
    <canvas id="canvas" style="display: none;"></canvas>
    <script>
        // Acessa a câmera do dispositivo
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (stream) {
                var video = document.getElementById('video');
                video.srcObject = stream;
                video.play();
            })
            .catch(function (error) {
                console.error('Erro ao acessar a câmera: ', error);
            });
    </script>
</body>
</html>
