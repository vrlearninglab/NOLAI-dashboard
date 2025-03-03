<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recording Playback</title>
</head>
<body>
    <h1>Recording Playback</h1>
    <button onclick="startPlayback()">Start Playback</button>
    <img id="playbackImage" src="" alt="Playback" style="width: 100%; max-width: 800px; border: 2px solid black;">

    <script>
        let images = [];
        let index = 0;
        let playbackInterval;

        async function fetchImages() {
            const response = await fetch('/get-images');
            images = await response.json();
        }

        async function startPlayback() {
            await fetchImages();
            if (images.length === 0) {
                alert("Geen afbeeldingen gevonden!");
                return;
            }

            index = 0;
            clearInterval(playbackInterval);
            playbackInterval = setInterval(() => {
                if (index >= images.length) {
                    clearInterval(playbackInterval);
                    return;
                }
                document.getElementById("playbackImage").src = "/storage/" + images[index];
                index++;
            }, 100);
        }
    </script>
</body>
</html>