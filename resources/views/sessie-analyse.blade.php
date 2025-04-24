<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessie Analyse</title>
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/recording.js') }}" defer></script>
    <script src="{{ asset('js/notes.js') }}" defer></script>
</head>
<body data-session-id="{{ $session->id }}" data-full-time="{{ $session->timer->full_time }}">
    <header>
        <a href="{{ url('/select-session') }}" class="return-button">
            <img src="{{ asset('img/icon_return.png') }}" alt="Terug" width="40" height="40">
            <span>Terug</span>
        </a>
        <h1>VR-woordenschat dashboard</h1>
    </header>

    <article class="sessie-info">
        <h1>Sessie Analyse</h1>
        
        <section class="sessie-details">
            <p>Sessie ID: {{ $session->id }}</p>
            <p>Datum aangemaakt: {{ $session->created_at }}</p>
            <p>Naam Onderzoeker: {{ $session->user->name }}</p>
            <p>Studentnummer: {{ $session->student->student_nummer }}</p>
        </section>
    </article>

    <article class="sessie-controls">
        <section class="livestream">
            <h2>Recording Playback</h2>

            <div class="playback-container">
                <img id="playbackImage" alt="Playback">
            </div>

            <button onclick="startPlayback()" id="js-livestream-button">▶ Start</button>

            <div class="media-controls" id="js-livestream-handles">
                <div>
                    <label for="timeFrameInput">Frames (ms):</label>
                    <input type="number" id="timeFrameInput" value="201" min="1" step="10">

                    <button id="updateTimeFrameButton" class="media-button">Update</button>
                </div>

                <div class="progress-container">
                    <button onclick="pauseHandler()" id="js-pausebutton" class="media-button">⏸</button>
                    <input type="range" id="progressBar" value="0" min="0" step="1">
                    <p><span id="currentTimeDisplay">0:00</span>&nbsp;/&nbsp;{{ $session->timer->full_time }}</p>
                </div>
            </div>
        </section>

        <section class="sessie-notes">
            <h1>Notes</h1>
            <ul id="notesList">
                <!-- Notities komen hier -->
            </ul>
            <div class="note-input-container">
                <input type="text" id="noteInput" placeholder="Typ een notitie...">
                <button onclick="addNote()">Toevoegen</button>
            </div>
        </section>
    </article>

    <footer>
    </footer>

    <script>
        var sessionId = {{ $session->id }};
    </script>
</body>
</html>