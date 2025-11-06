<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Live Unity Stream</title>
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    <script src="{{ asset('js/axios.min.js') }}"></script>
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
        <button onclick="openPopup()" class="stop-stream">Sessie beëindigen</button>
    </header>

    <article class="sessie-info">
        <h1>Sessie dashboard</h1>
        <section class="sessie-details">
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
            <button onclick="CreateActionButtons()" style="width:min-content;">⟳</button>
            <section id="sessie-buttons-content">
                <h3>Unity Acties</h3>
                <p>❌ Er zijn momenteel geen acties gedefineert. Ververs de actie knoppen</p>
            </section>
        </section>

        <section class="livestream">
            <h2>Live Unity Stream</h2>
            <div>
                <!-- Dit moet automatisch runnen op het momement dat de stream url bestaat -->
                @if ($streamURL)
                    <img src="{{ $streamURL }}" alt="">
                @else
                    <p>Geen stream url gevonden.</p>
                @endif
            </div>
            <div>
                <!-- <button onclick="startStream()">Start recorden en streamen</button> -->
                <span id="timer">00:00</span>
            </div>
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

     <!-- De pop-up overlay -->
    <article class="popup-overlay" id="popupOverlay">
        <section class="popup" id="popupOverlayConfirm">
            <h2>Sessie beëindigen</h2>
            <p>Weet je zeker dat je de sessie wilt beëindigen?</p>
            <section class="popup-buttons">
                <button class="cancel-btn" onclick="closePopup()">Annuleren</button>
                <button class="confirm-btn" onclick="confirmAndSendToUnity()">Bevestigen</button>
            </section>
        </section>

        <section class="popup popup-save" id="popupOverlaySave">
            <h2>Sessie beëindigen</h2>
            <p>Gegevens worden opgeslagen...</p>
            <button class="confirm-btn" disabled>Home</button>
        </section>
    </article>

    <footer>
    </footer>
    <script>
        window.sessionId = {{ $session->id ?? 'null' }}
    </script>
</body>
</html>
