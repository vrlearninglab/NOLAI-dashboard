<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Audio;
use App\Models\Session;

class AudioController extends Controller
{
    public function uploadAudio(Request $request)
    {
        // Haal de laatst aangemaakte sessie op (op basis van de timestamp)
        $latestSession = Session::latest()->first();
        if (!$latestSession) {
            return response()->json(['error' => 'Geen actieve sessie gevonden.'], 404);
        }

        if ($request->hasFile('ingame_audio') && $request->file('ingame_audio')->isValid()) {
            $gameFile = $request->file('ingame_audio');
            $gameFilename = uniqid() . '_game.wav';
            $gamePath = $gameFile->storeAs('audio_files', $gameFilename, 'public');
            Audio::create([
                'file_path' => $gamePath,
                'audio_type' => 'ingame',
                'session_id' => $latestSession->id, // Koppel de image aan de laatste sessie
            ]);
        }

        if ($request->hasFile('microphone_audio') && $request->file('microphone_audio')->isValid()) {
            $micFile = $request->file('microphone_audio');
            $micFilename = uniqid() . '_mic.wav';
            $micPath = $micFile->storeAs('audio_files', $micFilename, 'public');
            Audio::create([
                'file_path' => $micPath, 
                'audio_type' => 'microphone',
                'session_id' => $latestSession->id, // Koppel de image aan de laatste sessie
            ]);
        }

        return response()->json(['message' => 'Bestanden succesvol geüpload!']);
    }
}
