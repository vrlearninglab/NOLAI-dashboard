<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timer;

class TimerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'full_time' => 'required|string',
            'session_id' => 'required|exists:sessions,id'
        ]);

        $timer = Timer::create([
            'full_time' => $request->full_time,
            'session_id' => $request->session_id
        ]);

        return response()->json(['message' => 'Timer opgeslagen!', 'timer' => $timer], 201);
    }
}
