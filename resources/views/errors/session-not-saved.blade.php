<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessie niet opgeslagen</title>
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
</head>
<body>
    <header>
        <a href="{{ url('/select-session') }}" class="return-button">
            <img src="{{ asset('img/icon_return.png') }}" alt="Terug" width="40" height="40">
            <span>Terug</span>
        </a>
        <h1>VR-woordenschat dashboard</h1>
    </header>

    <article class="error-message">
        <h1>Sessie niet opgeslagen</h1>
        <p>
            Er is een sessie gestart, maar deze is niet beeindigt met de "sessie beeindigen" knop.
            Hierdoor zijn de timer en andere gegevens niet opgeslagen en gekoppeld aan de sessie.
        </p>
        <p>
            Ga terug en probeer het opnieuw, of start een nieuwe sessie.
        </p>

    </article>
</body>
</html>
