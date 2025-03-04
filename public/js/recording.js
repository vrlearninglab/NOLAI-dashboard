document.addEventListener('DOMContentLoaded', function () {
    let images = [];
    let index = 0;
    let playbackInterval;

    async function fetchImages() {
        try {
            const response = await fetch('/get-images');
            images = await response.json();
        } catch (error) {
            console.error('Fout bij het ophalen van de afbeeldingen:', error);
        }
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

    window.startPlayback = startPlayback;
});
