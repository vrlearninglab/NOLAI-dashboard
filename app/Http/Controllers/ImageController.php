<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Session;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Sla het bestand op in de public/images map
        $path = $request->file('image')->store('images', 'public');

        // Haal de laatst aangemaakte sessie op (op basis van de timestamp)
        $latestSession = Session::latest()->first();

        if (!$latestSession) {
            return response()->json(['error' => 'Geen actieve sessie gevonden.'], 404);
        }

        // Maak de afbeelding aan met de gekoppelde sessie
        $image = Image::create([
            'path' => $path,
            'session_id' => $latestSession->id, // Koppel de image aan de laatste sessie
        ]);

        return response()->json(['message' => 'Afbeelding opgeslagen!', 'image' => $image]);
    }

    public function showRecording()
    {
        return view('recording');
    }

    // Haal de afbeeldingen op die gelinkt zijn aan een specifieke sessie
    public function getImages($sessionId)
    {
        // Haal de afbeeldingen van de specifieke sessie op
        $images = Image::where('session_id', $sessionId)
                       ->orderBy('created_at')
                       ->pluck('path');

        // Return de lijst van afbeeldingen
        return response()->json($images);
    }
}