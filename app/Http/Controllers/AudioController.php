<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Audio;
use App\Models\Session;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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

    public function uploadMicrophoneAudio(Request $request)
    {
        //check of er een audio bestand is verstuurd
        if (!$request->hasFile('microphone_audio') || !$request->file('microphone_audio')->isValid()) {
            return response()->json([
                'success' => false,
                'error' => 'Ongeldig audiobestand.',
            ], 400);
        }

        
        // Sla het audiobestand op
        $micFile = $request->file('microphone_audio');
        $micFilename = uniqid() . '_mic.wav';
        $micPath = $micFile->storeAs('audio_files', $micFilename, 'public');


        // Stuur het audiobestand naar Speaches voor transcriptie
       $transcription = $this->transcribeAudio($micPath);

        if ($transcription) {
            return response()->json([
                'success' => true,
                'message' => 'Audiobestand succesvol ontvangen en getranscribeerd.',
                'transcription' => $transcription,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Fout bij het transcriberen van het audiobestand.',
            ], 500);
        }
    }


    private function transcribeAudio($audioPath)
    {
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post('http://speaches:8000/v1/audio/transcriptions', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen(storage_path('app/public/' . $audioPath), 'r'),
                        'filename' => 'audio.wav',
                    ],
                    [
                        'name' => 'model',
                        'contents' => 'Zoont/faster-whisper-large-v3-turbo-int8-ct2',
                    ],
                    [
                        'name' => 'language',
                        'contents' => 'nl',
                    ],
                    [
                        'name' => 'response_format',
                        'contents' => 'text',
                    ],
                ]
            ]);

            $responseBody = $response->getBody()->getContents();
            \Log::info("Transcription response: " . $responseBody);
            return $responseBody;

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error("Fout bij transcriberen: " . $e->getMessage());
            if ($e->hasResponse()) {
                \Log::error("Response: " . $e->getResponse()->getBody()->getContents());
            }
            return null;
        }
    }



    
}
