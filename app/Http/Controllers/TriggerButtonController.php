<?php

//Controller to receive Unity request for creating buttons, and storing them to dynamically create buttons on the dashboard

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TriggerButtonController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->json()->all();
        Cache::put('trigger_buttons', $data, now()->addMinutes(120)); //assuming after 2 hours the scene will be closed anyway
        return response()->json(['message' => 'Data stored successfully'], 200);
    }

    public function show()
    {
        $data = Cache::get('trigger_buttons', []);
        return response()->json(['data' => $data], 200);
    }
}
