<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Placeholder;

class PlaceholderController extends Controller
{
    public function storeData(Request $request)
    {
        Log::info('Ontvangen request:', $request->all());

        $validated = $request->validate([
            'data' => 'required|string|max:255',
        ]);

        $placeholder = new Placeholder();
        $placeholder->dummy_data = $request->input('data');
        $placeholder->save();

        return response()->json(['message' => 'Data succesvol opgeslagen']);
    }
}
