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

    public function saveStudent($studentNumber)
    {
        // Controleer of het leerlingnummer al bestaat
        $existingStudent = Student::where('student_nummer', $studentNumber)->first();
    
        // Als de student nog niet bestaat, voeg deze toe
        if (!$existingStudent) {
            Student::create([
                'student_nummer' => $studentNumber,
            ]);
        }
    }    
}
