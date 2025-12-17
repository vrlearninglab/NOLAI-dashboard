<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    <title>Startpagina</title>
</head>
<body>
    <header>
        <h1>Dashboard 'Woordenschat met VR en AI'</h1>
    </header>

    <section class="user-input">
        <img src="img/icon_user.png" alt="user-icon" width="100" height="100"> 
        <h1>Voer je naam in</h1>
        <form action="{{ route('store.name') }}" method="POST">
            @csrf
            <input type="text" id="name" name="name" required>
            <button type="submit">Start sessie</button>
        </form>
    </section>

    <footer>
    </footer>
</body>
</html>
