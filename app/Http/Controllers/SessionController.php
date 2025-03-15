<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function newSession($studentNumber)
    {
        // Haal de student op op basis van het studentnummer
        $student = Student::where('student_nummer', $studentNumber)->first();

        // Haal de user_id op uit de session
        $userName = session('username');
        $user = \App\Models\User::where('name', $userName)->first();

        if ($student && $user) {
            // Maak een nieuwe session row in de database
            Session::create([
                'student_id' => $student->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
