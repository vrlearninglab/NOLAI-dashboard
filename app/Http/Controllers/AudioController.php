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

        \Log::info("er is een audio bestand");  

        // Sla het audiobestand op
        $micFile = $request->file('microphone_audio');
        $micFilename = uniqid() . '_mic.wav';
        $micPath = $micFile->storeAs('audio_files', $micFilename, 'public');


        // Stuur het audiobestand naar Speaches voor transcriptie
        $transcription = $this->transcribeAudio($micPath);

        # check of er een transcriptie is gemaakt
        if (!$transcription) {
            return response()->json([
                'success' => false,
                'error' => 'Fout bij het transcriberen van het audiobestand.',
            ], 500);
        } 
        
        \Log::info("Transcription wordt doorgestuurd naar AI");  

        // stuur de transcriptie naar ai voor een antwoord
        $question = "de loopplank zorgt dat we van de kade op de boot komen";      #hardcoded tijdelijke vraag
        $airesult = $this->evaluateAnswerWithAI($transcription, $question);

        #check of er een ai beoordeling is
        if ($airesult !== null) {
            \Log::info("AI result value: " . print_r($airesult, true));
            return response()->json([
                'success' => true,
                'message' => 'Beoordeling succesvol.',
                'evaluation' => $airesult,  // 0 of 1
                'transcription' => $transcription,
            ]);
        } else {
            return response()->json([
                'success'=> false,
                'error' => 'fout bij het evalueren met Ai',
            ], 500);
        }
    }

    # roep speaches aan om de audio te transcriberen
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



    private function evaluateAnswerWithAI($transcription, $question)
    {
        $client = new \GuzzleHttp\Client();

        $prompt = <<<EOD
        Je bent een simpel taalmodel. Je beoordeelt of twee zinnen dezelfde betekenis hebben.
        Geef alleen 1 als output als de zinnen dezelfde betekenis hebben, en 0 als ze verschillen. Geef een korte uitleg.

        Voorbeeld 1:
        Zin 1: Het anker zorgt dat de boot stil blijft liggen.
        Zin 2: Anker zorgt dat de boot op zijn plek blijft.
        Output: 1, het kind zegt iets over op zijn plek blijven, dit is hetzelfde als stil blijven liggen

        Voorbeeld 2:
        Zin 1: De bal is blauw.
        Zin 2: De bal is rond.
        Output: 0, het kind zegt iets over de vorm (rond), maar moet iets zeggen over de kleur (blauw)

        Zin 1: $question
        Zin 2: $transcription
        Output:
        EOD;

         try {
            $response = $client->post('http://host.docker.internal:8002/evaluate', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'prompt' => $prompt,
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            \Log::info("AI response: " . json_encode($result));

            // Check of het antwoord 'false' of 'correct' bevat
            if (isset($result['result'])) {
                $aiOutput = strtolower($result['result']);
                if (strpos($aiOutput, '0')) {
                    return 0;
                } elseif (strpos($aiOutput, '1') ) {
                    return 1;
                }
            }

            // Als geen van beide gevonden wordt, geef standaard 0 terug
            return 0;

        } catch (\Exception $e) {
            \Log::error("AI evaluation failed: " . $e->getMessage());
            return null;
        }


    }
    
}
