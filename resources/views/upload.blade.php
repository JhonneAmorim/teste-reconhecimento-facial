<!DOCTYPE html>
<html>
<head>
    <title>Reconhecimento Facial</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Obtém o token CSRF do objeto window.Laravel
        var csrfToken = '{{ csrf_token() }}';

        // Configura o token CSRF em todas as solicitações AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
    </script>
</head>
<body>
    <h1>Reconhecimento Facial</h1>

    <div id="cameraView">
        <video id="video" autoplay></video>
    </div>

    <div id="successMessage" style="display: none;">
        <h2>Foto Salva com Sucesso!</h2>
    </div>

    <script>
        // Acessa a câmera do dispositivo
        navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then(function(stream) {
            var video = document.getElementById('video');
            video.srcObject = stream;
            video.play();

            // Captura a imagem da câmera assim que estiver disponível
            var canvas = document.createElement('canvas');
            var context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Converte a imagem do canvas para base64
            var base64Image = canvas.toDataURL('image/jpeg');

            // Envia a imagem via AJAX para o método uploadImage
            $.ajax({
                url: "{{ route('facial-recognition.upload') }}",
                type: "POST",
                data: {
                    image: base64Image
                },
                success: function(response) {
                    // Exibe a mensagem de sucesso
                    $('#cameraView').hide();
                    $('#successMessage').show();

                    // Redireciona para a página de sucesso após 2 segundos
                    setTimeout(function() {
                        window.location.href = "{{ route('success') }}";
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao enviar a imagem: ', error);
                }
            });
        })
        .catch(function(error) {
            console.error('Erro ao acessar a câmera: ', error);
        });
    </script>
</body>
</html>
