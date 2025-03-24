<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecteer Sessie</title>
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <header>
        <a href="{{ url('/') }}" class="return-button">
            <img src="{{ asset('img/icon_return.png') }}" alt="Terug" width="40" height="40">
            <span>Terug</span>
        </a>
        <h1>VR-woordenschat dashboard</h1>
    </header>
    <h1>Selecteer een sessie</h1>

    <!-- Filter opties met checkboxes -->
    <div>
        <label><input type="checkbox" id="filterId" checked> ID</label>
        <label><input type="checkbox" id="filterDate" checked> Datum</label>
        <label><input type="checkbox" id="filterResearcher" checked> Onderzoeker</label>
        <label><input type="checkbox" id="filterStudent" checked> Studentnummer</label>
    </div>

    <!-- Zoekbalk -->
    <input type="text" id="searchInput" placeholder="Zoek..." onkeyup="filterSessions()">

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Aangemaakt op</th>
                <th>Onderzoeker</th>
                <th>Studentnummer</th>
            </tr>
        </thead>
        <tbody id="sessionTableBody">
            <!-- Dynamische sessies worden hier geladen -->
        </tbody>
    </table>

    <script src="{{ asset('js/sessie-lijst.js') }}"></script>
</body>
</html>
