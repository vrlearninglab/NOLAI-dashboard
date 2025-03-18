<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Note;
use App\Models\Session;

class NoteController extends Controller
{
    public function index()
    {
        $latestSession = Session::latest()->first();
        if (!$latestSession) {
            return response()->json(['error' => 'Geen actieve sessie gevonden.'], 404);
        }

        $notes = Note::where('session_id', $latestSession->id)
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

    public function store(Request $request)
    {
        $latestSession = Session::latest()->first();
        if (!$latestSession) {
            return response()->json(['error' => 'Geen actieve sessie gevonden.'], 404);
        }

        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $note = Note::create([
            'message' => $request->message,
            'session_id' => $latestSession->id,
        ]);

        return response()->json($note, 201);
    }
}
