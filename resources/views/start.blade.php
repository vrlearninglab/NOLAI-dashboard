<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startpagina</title>
</head>
<body>
    <h1>Voer je naam in</h1>
    
    <form action="{{ route('store.name') }}" method="POST">
        @csrf
        <label for="name">Je naam:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">Start sessie</button>
    </form>
</body>
</html>
