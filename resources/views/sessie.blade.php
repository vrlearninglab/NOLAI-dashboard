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
    <img src="http://192.168.2.7:5000/stream/" alt="Stream currently not running, reload the page to try again." style="width: 100%; max-width: 800px; border: 2px solid black;">
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