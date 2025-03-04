<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Audio; // Zorg ervoor dat je een model hebt voor de database

class AudioController extends Controller
{
    public function uploadAudio(Request $request)
    {
        // Controleer of er een bestand is geüpload
        if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
            $audioFile = $request->file('audio');
            $filename = uniqid() . '.wav'; // Unieke bestandsnaam genereren
            $path = $audioFile->storeAs('audio_files', $filename, 'public'); // Opslaan in storage/app/public/audio_files/

            // Opslaan in database
            $audio = new Audio();
            $audio->file_path = $path;
            $audio->save();

            return response()->json(['message' => 'Bestand succesvol geüpload!', 'file_path' => $path]);
        }

        return response()->json(['error' => 'Geen geldig bestand geüpload!'], 400);
    }
}
