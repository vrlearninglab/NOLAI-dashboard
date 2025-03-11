<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Live Unity Stream</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <h1>Welkom op het Dashboard</h1>

    <h2>Live Unity Stream</h2>

    <button onclick="sendToUnity('Start stream')">Start stream</button>
    <button onclick="sendToUnity('Stop stream')">Stop stream</button>
    <img src="http://127.0.0.1:5000/stream/" alt="Stream currently not running, reload the page to try again." style="width: 100%; max-width: 800px; border: 2px solid black;">
</body>
</html>