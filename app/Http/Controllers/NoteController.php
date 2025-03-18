<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Note;
use App\Models\Session;

class NoteController extends Controller
{
    public function index($sessionId = null)
    {
        // Gebruik de opgegeven sessie-ID of pak de laatst aangemaakte sessie
        if ($sessionId) {
            $session = Session::find($sessionId);
        } else {
            $session = Session::latest()->first();
        }

        if (!$session) {
            return response()->json(['error' => 'Geen sessie gevonden.'], 404);
        }

        // Haal de notities op voor de juiste sessie
        $notes = Note::where('session_id', $session->id)
                    ->oldest()
                    ->get()
                    ->map(function ($note) {
                        return [
                            'message' => $note->message,
                            'created_at' => Carbon::parse($note->created_at)->format('Y-m-d H:i:s')
                        ];
                    });

        return response()->json($notes);
    }

    public function store(Request $request, $sessionId = null)
    {
        // Gebruik de opgegeven sessie-ID of pak de laatst aangemaakte sessie
        if ($sessionId) {
            $session = Session::find($sessionId);
        } else {
            $session = Session::latest()->first();
        }

        if (!$session) {
            return response()->json(['error' => 'Geen sessie gevonden.'], 404);
        }

        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $note = Note::create([
            'message' => $request->message,
            'session_id' => $session->id,
        ]);

        return response()->json($note, 201);
    }
}
