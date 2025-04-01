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
<body>
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
            <div>
                <img id="playbackImage" alt="Playback">
            </div>
            <button onclick="startPlayback()">Start Playback</button>
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