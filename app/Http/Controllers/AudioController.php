<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Audio; // Zorg ervoor dat je een model hebt voor de database

class AudioController extends Controller
{
    public function uploadAudio(Request $request)
    {
        if ($request->hasFile('ingame_audio') && $request->file('ingame_audio')->isValid()) {
            $gameFile = $request->file('ingame_audio');
            $gameFilename = uniqid() . '_game.wav';
            $gamePath = $gameFile->storeAs('audio_files', $gameFilename, 'public');
            Audio::create(['file_path' => $gamePath, 'audio_type' => 'ingame']);
        }

        if ($request->hasFile('microphone_audio') && $request->file('microphone_audio')->isValid()) {
            $micFile = $request->file('microphone_audio');
            $micFilename = uniqid() . '_mic.wav';
            $micPath = $micFile->storeAs('audio_files', $micFilename, 'public');
            Audio::create(['file_path' => $micPath, 'audio_type' => 'microphone']);
        }

        return response()->json(['message' => 'Bestanden succesvol geüpload!']);
    }
}
