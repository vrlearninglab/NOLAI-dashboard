<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StreamSetting;
use App\Models\Session; // Voeg de Session model toe om sessie-informatie op te halen

class StreamController extends Controller
{
    public function updateStream(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip',
            'port' => 'required|numeric',
        ]);

        $setting = StreamSetting::first();
        if (!$setting) {
            $setting = new StreamSetting();
        }

        $setting->ip = $request->ip;
        $setting->port = $request->port;
        $setting->save();

        return response()->json(['message' => 'Stream settings updated successfully']);
    }

    public function getStream()
    {
        $streamSetting = StreamSetting::first();

        $streamURL = $streamSetting && $streamSetting->ip && $streamSetting->port
            ? "http://{$streamSetting->ip}:{$streamSetting->port}/stream/"
            : null;

        $session = Session::with(['user', 'student'])->latest()->first();

        if (!$session) {
            $session = null;
        }

        return view('sessie', compact('streamURL', 'session'));
    }

    public function checkStream()
    {
        $streamSetting = StreamSetting::first();

        $streamURL = $streamSetting && $streamSetting->ip && $streamSetting->port
            ? "http://{$streamSetting->ip}:{$streamSetting->port}/stream/"
            : null;

        return response()->json([
            'streamURL' => $streamURL
        ]);
    }
}
