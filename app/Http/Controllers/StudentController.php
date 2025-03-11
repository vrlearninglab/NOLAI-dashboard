<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Toon de pagina met de lijst van leerlingen
    public function showStudents()
    {
        $students = Student::all(); // Haal alle studenten op
        return view('students', compact('students')); // Zet ze door naar de view
    }

    // Verwerk de sessie en sla het leerlingnummer op (POST)
    public function startSession(Request $request)
    {
        // Haal het leerlingnummer uit het formulier
        $studentNumber = $request->input('student_number');

        // Controleer of het leerlingnummer al bestaat in de database
        $existingStudent = Student::where('student_nummer', $studentNumber)->first();

        // Als het leerlingnummer nog niet bestaat, voeg het dan toe
        if (!$existingStudent) {
            // Maak een nieuwe student aan
            Student::create([
                'student_nummer' => $studentNumber,
            ]);
        }

        // Redirect naar de sessiepagina
        return redirect('/sessie');
    }
}
