<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Api Latest</title>
    <script src="face-api.js"></script>
    <style>
        #myImg,#testImg{
            height: 96vh; 
            margin: 10px;
        }
    </style>
</head>
<body>
    <img id="myImg" src="images/example.png" />
    <img id="testImg" src="images/test1.jpg" />
    <!-- <video id="myVideo" src="media/example.mp4" /> -->
    <canvas id="overlay" />
</body>
<script>
    // console.log(faceapi.nets);
    const input = document.getElementById('myImg');

    async function runFaceDetection() {

        await faceapi.nets.ssdMobilenetv1.loadFromUri('./models');
        await faceapi.nets.faceLandmark68Net.loadFromUri('./models');
        await faceapi.nets.faceRecognitionNet.loadFromUri('./models');
        
        const result = await faceapi.detectSingleFace(input, new faceapi.SsdMobilenetv1Options()).withFaceLandmarks().withFaceDescriptor();

        if (!result) {
            return
        }

        // create FaceMatcher with automatically assigned labels
        // from the detection results for the reference image
        const faceMatcher = new faceapi.FaceMatcher(result.descriptor);
        console.log(faceMatcher);

        const singleResult = await faceapi
            .detectSingleFace(testImg)
            .withFaceLandmarks()
            .withFaceDescriptor()

        if (singleResult) {
            const bestMatch = faceMatcher.findBestMatch(singleResult.descriptor)
            console.log(bestMatch)
        }

        const displaySize = { width: input.width, height: input.height }
        // resize the overlay canvas to the input dimensions
        const canvas = document.getElementById('overlay')
        faceapi.matchDimensions(canvas, displaySize)

        // resize the detected boxes in case your displayed image has a different size than the original
        const resizedDetections = faceapi.resizeResults(singleResult, displaySize)
        // draw detections into the canvas
        faceapi.draw.drawDetections(canvas, resizedDetections)

        // resize the detected boxes and landmarks in case your displayed image has a different size than the original
        const resizedResults = faceapi.resizeResults(singleResult, displaySize)
        // draw detections into the canvas
        faceapi.draw.drawDetections(canvas, resizedResults)

    }
    runFaceDetection();
</script>
</html>