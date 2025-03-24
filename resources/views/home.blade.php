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
        <h1>VR-woordenschat dashboard</h1>
    </header>

    <section>
        <h1>Welkom, {{ session('username') }}!</h1>

        <a href="{{ route('start.sessie') }}" style="text-decoration: none; color: inherit;">
            <div style="display: flex; align-items: center; cursor: pointer;">
                <img src="{{ asset('img/icon_videography_2.png') }}" alt="icon_videography" width="100" height="100">
                <h2>Nieuwe sessie</h2>
            </div>
        </a>

        <a href="{{ route('sessions.index') }}" style="text-decoration: none; color: inherit;">
            <div style="display: flex; align-items: center; cursor: pointer;">
                <img src="{{ asset('img/icon_dashboard-monitor_2.png') }}" alt="icon_dashboard-monitor" width="100" height="100">
                <h2>Sessie analyseren</h2>
            </div>
        </a>
    </section>

    <footer>
    </footer>
</body>
</html>
