let images = [];
let index = 0;
let timeFrame = 201;
let playbackInterval;
let audio1, audio2;
let paused = false;
let audio1PausedAt = 0;
let audio2PausedAt = 0;

const fullPlaybackTime = parseInt(document.body.getAttribute('data-full-time'), 10); // Laravel blade → {{ $session->timer->full_time }}
const progressBar = document.getElementById('progressBar');

function getSessionId() {
    return document.body.getAttribute('data-session-id');
}

async function fetchImages(sessionId) {
    try {
        const response = await fetch(`/get-images/${sessionId}`);
        images = await response.json();
    } catch (error) {
        console.error('Fout bij het ophalen van de afbeeldingen:', error);
    }
}

async function fetchAudio(sessionId) {
    try {
        const response = await fetch(`/get-audio/${sessionId}`);
        const audioFiles = await response.json();

        if (audioFiles.length < 2) {
            console.error("Niet genoeg audiobestanden gevonden.");
            return;
        }

        audio1 = new Audio(`/storage/${audioFiles[0]}`);
        audio2 = new Audio(`/storage/${audioFiles[1]}`);

        await Promise.all([
            new Promise(resolve => audio1.addEventListener('canplaythrough', resolve, { once: true })),
            new Promise(resolve => audio2.addEventListener('canplaythrough', resolve, { once: true }))
        ]);
    } catch (error) {
        console.error('Fout bij het ophalen van de audiobestanden:', error);
    }
}

async function startPlayback() {
    const sessionId = getSessionId();
    await fetchImages(sessionId);
    await fetchAudio(sessionId);

    index = 0;
    paused = false;
    clearInterval(playbackInterval);

    audio1.currentTime = 0;
    audio2.currentTime = 0;

    audio1.play();
    audio2.play();

    setStreamButtons();

    progressBar.max = images.length - 1;
    progressBar.value = 0;

    playbackInterval = setInterval(updateFrame, timeFrame);
}

function updateFrame() {
    if (index >= images.length) {
        clearInterval(playbackInterval);
        return;
    }
    document.getElementById("playbackImage").src = "/storage/" + images[index];
    progressBar.value = index;
    index++;
}

function pauseHandler() {
    if (paused) {
        document.getElementById("js-pausebutton").innerHTML = "⏸";
        resumePlayback();
    } else {
        document.getElementById("js-pausebutton").innerHTML = "▶";
        pausePlayback();
    }
}

function pausePlayback() {
    if (audio1 && !audio1.paused) {
        audio1PausedAt = audio1.currentTime;
        audio1.pause();
    }

    if (audio2 && !audio2.paused) {
        audio2PausedAt = audio2.currentTime;
        audio2.pause();
    }

    if (playbackInterval) {
        clearInterval(playbackInterval);
    }

    paused = true;
}

function resumePlayback() {
    if (!paused) return;

    if (audio1) {
        audio1.currentTime = audio1PausedAt;
        audio1.play();
    }

    if (audio2) {
        audio2.currentTime = audio2PausedAt;
        audio2.play();
    }

    playbackInterval = setInterval(updateFrame, timeFrame);
    paused = false;
}

function setStreamButtons() {
    document.getElementById("js-livestream-button").style.display = "none";
    document.getElementById("js-livestream-handles").style.display = "flex";
}

document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('timeFrameInput');
    const button = document.getElementById('updateTimeFrameButton');

    button.addEventListener('click', () => {
        const newValue = parseInt(input.value);
        if (!isNaN(newValue) && newValue > 0) {
            timeFrame = newValue;
            console.log(`Nieuwe timeFrame: ${timeFrame} ms`);

            if (playbackInterval && !paused) {
                clearInterval(playbackInterval);
                playbackInterval = setInterval(updateFrame, timeFrame);
            }
        }
    });

    progressBar.addEventListener('input', function (e) {
        const newIndex = parseInt(e.target.value);
        index = newIndex;

        document.getElementById("playbackImage").src = "/storage/" + images[index];

        const percentage = index / images.length;
        if (audio1) audio1.currentTime = audio1.duration * percentage;
        if (audio2) audio2.currentTime = audio2.duration * percentage;
    });
});