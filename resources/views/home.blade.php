<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepagina</title>
</head>
<body>
    <h1>Welkom, {{ session('username') }}!</h1>
    <form action="{{ route('start.sessie') }}">
        <button type="submit">Nieuwe sessie</button>
    </form>
</body>
</html>
