<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leerlingnummers</title>
    <script>
        function filterStudents() {
            let input = document.getElementById('student_number').value.toLowerCase();
            let listItems = document.querySelectorAll('.student-item');

            listItems.forEach(function(item) {
                let studentNumber = item.textContent || item.innerText;
                if (studentNumber.toLowerCase().includes(input)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function fillInput(studentNumber) {
            document.getElementById('student_number').value = studentNumber;
            filterStudents(); // Filter meteen na het invullen
        }
    </script>
</head>
<body>
    <h1>Start een nieuwe sessie</h1>

    <!-- Controleer of er een foutmelding is -->
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Formulier voor het leerlingnummer -->
    <form action="{{ route('start.sessie') }}" method="POST">
        @csrf
        <label for="student_number">Leerlingnummer:</label>
        <input type="text" id="student_number" name="student_number" onkeyup="filterStudents()" required>
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
</body>
</html>