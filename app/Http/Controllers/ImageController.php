<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = $request->file('image')->store('images', 'public');

        $image = Image::create([
            'path' => $path
        ]);

        return response()->json(['message' => 'Afbeelding opgeslagen!', 'image' => $image]);
    }

    public function showRecording()
    {
        return view('recording');
    }

    public function getImages()
    {
        $images = \App\Models\Image::orderBy('created_at')->pluck('path');
        return response()->json($images);
    }
}