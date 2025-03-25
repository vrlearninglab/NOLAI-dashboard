<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leerlingnummers</title>
    <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
    <script src="{{ asset('js/select_student.js') }}" defer></script>
</head>
<body>
    <header>
        <a href="{{ url('/home/{name}') }}" class="return-button">
            <img src="{{ asset('img/icon_return.png') }}" alt="Terug" width="40" height="40">
            <span>Terug</span>
        </a>
        <h1>VR-woordenschat dashboard</h1>
    </header>
    <h1>Start een nieuwe sessie</h1>

    <section class="select-student">
        <!-- Formulier voor het leerlingnummer -->
        <form action="{{ route('start.sessie') }}" method="POST">
            @csrf
            <label for="student_number">Leerlingnummer:</label>
            <input type="text" id="student_number" name="student_number" onkeyup="filterStudents()" placeholder="Filter en selecteer" required>
            <button type="submit">Start sessie</button>
        </form>

        <h2>Beschikbare Leerlingnummers</h2>
        <ul>
            @foreach ($students as $student)
                <li class="student-item" onclick="fillInput('{{ $student->student_nummer }}')">
                    {{ $student->student_nummer }}
                </li>
            @endforeach
        </ul>
    </section>

    <footer>
    </footer>
</body>
</html>