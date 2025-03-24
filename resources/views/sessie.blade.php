<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Live Unity Stream</title>
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/sessie.js') }}" defer></script>
    <script src="{{ asset('js/notes.js') }}" defer></script>
</head>
<body>
    <header>
        <a href="{{ url('/home/{name}') }}" class="return-button">
            <img src="{{ asset('img/icon_return.png') }}" alt="Terug" width="40" height="40">
            <span>Terug</span>
        </a>
        <h1>VR-woordenschat dashboard</h1>
        <button onclick="sendToUnity('Stop stream')" class="stop-stream">Sessie beëindigen</button>
    </header>

    <article class="sessie-info">
        <h1>Sessie dashboard</h1>
        <section class="sessie-details">
            <p>Datum: 24-03-25</p>
            <p>Onderzoeker: Enrique</p>
            <p>Studentnr: s900019</p>
        </section>
    </article>

    <article class="sessie-controls">
        <section class="sessie-buttons">
            <h2>VR-besturing</h2>
            <section class="sessie-buttons-content">
                <h3>Geluid</h3>
                <button onclick="sendToUnity('a')">Start a</button>
                <button onclick="sendToUnity('b')">Start b</button>
                <button onclick="sendToUnity('c')">Start c</button>
                <button onclick="sendToUnity('d')">Start d</button>
                <button onclick="sendToUnity('e')">Start e</button>
                <button onclick="sendToUnity('f')">Start f</button>

                <h3>Scene</h3>
                <button onclick="sendToUnity('a')">Start a</button>
                <button onclick="sendToUnity('b')">Start b</button>
                <button onclick="sendToUnity('c')">Start c</button>
                <button onclick="sendToUnity('d')">Start d</button>
                <button onclick="sendToUnity('e')">Start e</button>
                <button onclick="sendToUnity('f')">Start f</button>
            </section>
        </section>

        <section class="livestream">
            <h2>Live Unity Stream</h2>
            @if ($streamURL)
                <img src="{{ $streamURL }}" alt="Stream niet gestart, of reload de pagina" width="950" height="500">
            @else
                <p>Geen stream url gevonden.</p>
            @endif
            <button onclick="sendToUnity('Start stream')">Start stream</button>
        </section>

        <section class="sessie-notes">
            <h1>Notes</h1>
            <br>
            <ul id="notesList">
                <!-- notities komen hier -->
            </ul>
            <input type="text" id="noteInput" placeholder="Typ een notitie...">
            <button onclick="addNote()">Toevoegen</button>
        </section>
    </article>

    <footer>
    </footer>
</body>
</html>