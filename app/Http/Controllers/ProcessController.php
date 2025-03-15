<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function newSession(Request $request)
    {
        $studentNumber = $request->input('student_number');

        // Maak een nieuwe instantie van StudentController
        $studentController = new StudentController();

        // Roep de saveStudent-methode aan en geef studentNumber mee
        $studentController->saveStudent($studentNumber);

        // Roep de SessionController aan
        $sessionController = new SessionController();
        $sessionController->newSession($studentNumber);

        return redirect('/sessie');
    }
}

