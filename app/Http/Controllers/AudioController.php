<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\Audio;
use App\Models\Session;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AudioController extends Controller
{
    public function uploadIngameAudio(Request $request)
    {
        \Log::info('Inkomende ingame audio request:', [
            'hasFile' => $request->hasFile('ingame_audio'),
            'isValid' => $request->hasFile('ingame_audio') ? $request->file('ingame_audio')->isValid() : 'N/A',
            'file' => $request->file('ingame_audio'),
        ]);

        // Haal de laatste sessie op
        $latestSession = Session::latest()->first();
        if (!$latestSession) {
            return response()->json(['error' => 'Geen actieve sessie gevonden.'], 404);
        }

        // Check of er een audiobestand is verstuurd
        if (!$request->hasFile('ingame_audio') || !$request->file('ingame_audio')->isValid()) {
            return response()->json([
                'success' => false,
                'error' => 'Ongeldig audiobestand.',
            ], 400);
        }

        \Log::info("er is een ingame audio bestand");
        // Sla ingame audio op
        $gameFile = $request->file('ingame_audio');
        $gameFilename = uniqid() . '_game.wav';
        $gamePath = $gameFile->storeAs('audio_files', $gameFilename, 'public');

        // Sla het bestand op in de database
        Audio::create([
            'file_path' => $gamePath,
            'audio_type' => 'ingame',
            'session_id' => $latestSession->id,
        ]);

        return response()->json(['message' => 'Ingame audio succesvol geüpload!']);
    }



    public function uploadMicrophoneAudio(Request $request)
    {
        // Haal de laatste sessie op
        $latestSession = Session::latest()->first();
        if (!$latestSession) {
            return response()->json(['error' => 'Geen actieve sessie gevonden.'], 404);
        }
        
        //check of er een audio bestand is verstuurd
        if (!$request->hasFile('microphone_audio') || !$request->file('microphone_audio')->isValid()) {
            return response()->json([
                'success' => false,
                'error' => 'Ongeldig audiobestand.',
            ], 400);
        }

        \Log::info("er is een audio bestand");
        \Log::info('Request fields:', $request->all());

        // Sla het audiobestand op
        $micFile = $request->file('microphone_audio');
        $micFilename = uniqid() . '_mic.wav';
        $micPath = $micFile->storeAs('audio_files', $micFilename, 'public');

        // Sla het bestand op in de database
        Audio::create([
            'file_path' => $micPath,
            'audio_type' => 'microphone',
            'session_id' => $latestSession->id,
        ]);

        $AskedQuestion = $request->input('AskedQuestion');
        $AnswerOptions = $request->input('AnswerOptions', '');

        // Stuur het audiobestand naar Speaches voor transcriptie
        $transcription = $this->transcribeAudio($micPath);

        # check of er een transcriptie is gemaakt
        if (!$transcription) {
            return response()->json([
                'success' => false,
                'error' => 'Fout bij het transcriberen van het audiobestand.',
            ], 500);
        }

        \Log::info("Transcription wordt doorgestuurd naar AI met volgende waarde: $transcription, $AskedQuestion, $AnswerOptions");

        // stuur de transcriptie naar ai voor een antwoord
        $airesult = $this->evaluateAnswerWithAI($transcription, $AskedQuestion, $AnswerOptions);

        #check of er een ai beoordeling is
        if ($airesult !== null) {
            // Zet de AI-evaluatie direct in de cache
            Cache::put('latest_ai_evaluation', [
                'evaluation' => $airesult,
            ], now()->addMinutes(5));
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Fout bij het evalueren met AI.',
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

    private function evaluateAnswerWithAI($transcription, $question, $AnswerOptions)
    {
        $client = new \GuzzleHttp\Client();

        // Convert array to string if needed
        if (is_array($AnswerOptions)) {
            $AnswerOptions = implode(', ', $AnswerOptions);
        }

        $userPrompt = <<<EOD
    Vraag: $question
    Antwoord kind: $transcription
    Opties: {$AnswerOptions}
    Output:
    EOD;

        try {
            $response = $client->post('http://host.docker.internal:11434/v1/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'StudentLLM',
                    'prompt' => $userPrompt,
                    'temperature' => 0.0,
                    'think' => false
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            \Log::info("AI response: " . json_encode($result));

            // ✅ Extract text safely
            if (isset($result['choices'][0]['text'])) {
                $aiOutput = trim($result['choices'][0]['text']);
                \Log::info("Extracted AI text: " . $aiOutput);
                return $aiOutput;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error("AI evaluation failed: " . $e->getMessage());
            return null;
        }
    }

    public function pullAIEvaluation()
    {
        // Haal de evaluatie uit de cache en verwijder deze
        $evaluation = Cache::pull('latest_ai_evaluation');

        // Stuur de evaluatie terug
        return response()->json([
            'evaluation' => $evaluation['evaluation'] ?? $evaluation ?? null,
        ]);
    }
}
