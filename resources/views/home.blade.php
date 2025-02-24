<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - home</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <h1>Welkom op het Dashboard</h1>
    <button onclick="sendToUnity('Hello world')">Bericht 1</button>
    <button onclick="sendToUnity('from laravel')">Bericht 2</button>
    <button onclick="sendToUnity('for unity')">Bericht 3</button>
</body>
</html>
