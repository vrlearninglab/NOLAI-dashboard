document.addEventListener('DOMContentLoaded', function () {
    let images = [];
    let index = 0;
    let playbackInterval;
    let audio1, audio2; // Twee audio-objecten
    let sessionId = window.sessionId; // Haal sessie-ID op

    async function fetchImages() {
        try {
            const response = await fetch(`/get-images/${sessionId}`);
            images = await response.json();
        } catch (error) {
            console.error('Fout bij het ophalen van de afbeeldingen:', error);
        }
    }

    async function fetchAudio() {
        try {
            const response = await fetch(`/get-audio/${sessionId}`);
            const audioFiles = await response.json();

            if (audioFiles.length < 2) {
                console.error("Niet genoeg audiobestanden gevonden.");
                return;
            }

            audio1 = new Audio(`/storage/${audioFiles[0]}`);
            audio2 = new Audio(`/storage/${audioFiles[1]}`);

            // Wacht tot beide geladen zijn en start ze dan tegelijk
            await Promise.all([audio1.load(), audio2.load()]);
        } catch (error) {
            console.error('Fout bij het ophalen van de audiobestanden:', error);
        }
    }

    async function startPlayback() {
        await fetchImages();
        await fetchAudio();

        if (!audio1 || !audio2) {
            alert("Geluid kan niet worden afgespeeld!");
            return;
        }

        index = 0;
        clearInterval(playbackInterval);

        // Start beide audios tegelijk
        audio1.play();
        audio2.play();

        playbackInterval = setInterval(() => {
            if (index >= images.length) {
                clearInterval(playbackInterval);
                return;
            }
            document.getElementById("playbackImage").src = "/storage/" + images[index];
            index++;
        }, 202);
    }

    window.startPlayback = startPlayback;
});