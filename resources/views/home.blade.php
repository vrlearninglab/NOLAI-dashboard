<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    <title>Homepagina</title>
</head>
<body>
    <header>
        <a href="{{ url('/') }}" class="return-button">
            <img src="{{ asset('img/icon_return.png') }}" alt="Terug" width="40" height="40">
            <span>Terug</span>
        </a>
        <h1>VR-woordenschat dashboard</h1>
    </header>

    <section class="welcome-section">
        <h1>Welkom, {{ session('username') }}!</h1>

        <a href="{{ route('start.sessie') }}" class="welcome-link">
            <div class="welcome-option">
                <img src="{{ asset('img/icon_videography_2.png') }}" alt="icon_videography">
                <h2>Nieuwe sessie</h2>
            </div>
        </a>

        <a href="{{ route('sessions.index') }}" class="welcome-link">
            <div class="welcome-option">
                <img src="{{ asset('img/icon_dashboard-monitor_2.png') }}" alt="icon_dashboard-monitor">
                <h2>Sessie analyseren</h2>
            </div>
        </a>
    </section>

    <footer>
    </footer>
</body>
</html>
