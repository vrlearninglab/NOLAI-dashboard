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
            <!-- Toon de sessie-informatie hier -->
            @if ($session)
                <p>Datum: {{ $session->created_at }}</p>
                <p>Onderzoeker: {{ $session->user->name }}</p>
                <p>Studentnr: {{ $session->student->student_nummer }}</p>
            @else
                <p>Geen sessie gevonden.</p>
            @endif
        </section>
    </article>

    <article class="sessie-controls">
        <section class="sessie-buttons">
            <h2>VR-besturing</h2>
            <section id="sessie-buttons-content">
                <button onclick="CreateActionButtons()" style="width:min-content; gab: 1em;">⟳</button>
                <h3>Unity Acties</h3>
                <p>❌ Er zijn momenteel geen acties gedefineert. Ververs de actie knoppen</p>
                
            </section>
        </section>

        <section class="livestream">
            <h2>Live Unity Stream</h2>
            <div>
                @if ($streamURL)
                    <img src="{{ $streamURL }}" alt="Stream niet gestart, of reload de pagina">
                @else
                    <p>Geen stream url gevonden.</p>
                @endif
            </div>
            <button onclick="sendToUnity('Start stream')">Start recorden en streamen</button>
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
