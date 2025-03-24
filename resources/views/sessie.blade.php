<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Live Unity Stream</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/sessie.js') }}" defer></script>
    <script src="{{ asset('js/notes.js') }}" defer></script>
</head>
<body>
    <h1>Welkom op het Dashboard</h1>

    <h2>Live Unity Stream</h2>

    <button onclick="sendToUnity('Start stream')">Start stream</button>
    <button onclick="sendToUnity('Stop stream')">Stop stream</button>
    @if ($streamURL)
        <img src="{{ $streamURL }}" alt="Stream niet gestart, of reload de pagina" style="width: 100%; max-width: 800px; border: 2px solid black;">
    @else
        <p>Geen stream url gevonden.</p>
    @endif
</body>

<div>
    <h1>Notes</h1>

    <input type="text" id="noteInput" placeholder="Typ een notitie...">
    <button onclick="addNote()">Toevoegen</button>

    <ul id="notesList">
        <!-- Notities worden hier geladen -->
    </ul>
</div>
</html>