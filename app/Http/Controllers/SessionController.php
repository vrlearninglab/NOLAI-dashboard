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

    public function index()
    {
        return view('select-session');
    }

    public function getSessions()
    {
        $sessions = Session::with(['user', 'student'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'created_at' => $session->created_at->format('Y-m-d H:i:s'),
                    'researcher_name' => $session->user->name ?? 'Onbekend',
                    'student_number' => $session->student->student_nummer ?? 'Onbekend',
                ];
            });

        return response()->json($sessions);
    }

    // Toon de details van een specifieke sessie
    public function show($id)
    {
        $session = Session::with(['user', 'student', 'timer'])->find($id);

        if (!$session) {
            return redirect()->route('select-session')->with('error', 'Sessie niet gevonden.');
        }

        // Controleer of de timer ontbreekt
        if (!$session->timer) {
            return view('errors.session-not-saved');
        }

        // Als alles oké is, toon de normale view
        return view('sessie-analyse', compact('session'));
    }

}
