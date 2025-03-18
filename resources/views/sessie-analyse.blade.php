<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessie Analyse</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/recording.js') }}" defer></script>
    <script src="{{ asset('js/notes.js') }}" defer></script>
</head>
<body>
    <h1>Sessie Analyse</h1>
    
    <!-- Toon de sessie-informatie -->
    <p><strong>Sessie ID:</strong> {{ $session->id }}</p>
    <p><strong>Datum aangemaakt:</strong> {{ $session->created_at }}</p>
    <p><strong>Naam Onderzoeker:</strong> {{ $session->user->name }}</p>
    <p><strong>Studentnummer:</strong> {{ $session->student->student_nummer }}</p>

    <div>
        <h1>Recording Playback</h1>
        <button onclick="startPlayback()">Start Playback</button>
        <img id="playbackImage" src="" alt="Playback" style="width: 100%; max-width: 800px; border: 2px solid black;">
    </div>

    <div>
        <h1>Notes</h1>

        <input type="text" id="noteInput" placeholder="Typ een notitie...">
        <button onclick="addNote()">Toevoegen</button>

        <ul id="notesList">
            <!-- Notities worden hier geladen -->
        </ul>
    </div>

    <a href="{{ route('sessions.index') }}">Terug naar sessies</a>

    <script>
        var sessionId = {{ $session->id }};
    </script>
</body>
</html>