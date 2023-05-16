const cam = document.querySelector('#video');
let isFaceDetected = false;

Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
  faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
  faceapi.nets.faceExpressionNet.loadFromUri('/models')
]).then(startVideo).catch(error => {
  console.error('Erro ao carregar modelos da face-api:', error);
});

async function startVideo() {
  const constraints = { video: true };

  try {
    const stream = await navigator.mediaDevices.getUserMedia(constraints);
    cam.srcObject = stream;

    cam.addEventListener('loadeddata', () => {
      cam.play();
      startFaceDetection();
    });
  } catch (error) {
    console.error('Erro ao acessar a câmera:', error);
  }
}

function startFaceDetection() {
  const canvas = faceapi.createCanvasFromMedia(cam);
  document.body.append(canvas);

  const displaySize = { width: cam.videoWidth, height: cam.videoHeight };
  faceapi.matchDimensions(canvas, displaySize);

  const captureButton = document.createElement('button');
  captureButton.innerText = 'Capturar Rosto';
  document.body.append(captureButton);
  captureButton.addEventListener('click', captureFace);

  setInterval(async () => {
    const detections = await faceapi.detectAllFaces(
      cam,
      new faceapi.TinyFaceDetectorOptions()
    )
      .withFaceLandmarks()
      .withFaceExpressions();

    const resizedDetections = faceapi.resizeResults(detections, displaySize);

    canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

    if (resizedDetections && resizedDetections.length > 0) {
      isFaceDetected = true;
    //   faceapi.draw.drawDetections(canvas, resizedDetections);
    //   faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
    //   faceapi.draw.drawFaceExpressions(canvas, resizedDetections);
    } else {
      isFaceDetected = false;
    }
  }, 100);

  function captureFace() {
    if (isFaceDetected) {
      const context = canvas.getContext('2d');
      context.drawImage(cam, 0, 0, canvas.width, canvas.height);

      canvas.toBlob(function (blob) {
        const formData = new FormData();
        formData.append('image', blob, 'face.jpg');

        // Envie o formulário com o arquivo de imagem para o backend
        fetch('/save-face', {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
          .then(response => {
            if (response.ok) {
              alert('Rosto capturado e enviado para o backend com sucesso!');
            } else {
              throw new Error('Falha ao enviar o rosto para o backend. Código de resposta: ' + response.status);
            }
          })
          .catch(error => {
            console.error('Erro ao enviar o rosto para o backend:', error);
          });
      }, 'image/jpeg');
    } else {
      console.log('Nenhum rosto detectado. Não é possível capturar a imagem.');
    }
  }
}
