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
    <img src="http://127.0.0.1:5000/stream/" alt="Unity Stream" style="width: 100%; max-width: 800px; border: 2px solid black;">

    <button onclick="sendToUnity('Hello world')">Bericht 1</button>
    <button onclick="sendToUnity('from laravel')">Bericht 2</button>
    <button onclick="sendToUnity('for unity')">Bericht 3</button>

    <script>
        async function sendToUnity(message) {
            try {
                const response = await fetch('/send-to-unity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: message })
                });
                const data = await response.json();
                alert(data.message);
            } catch (error) {
                console.error('Fout bij het versturen van data:', error);
            }
        }
    </script>
</body>
</html>
