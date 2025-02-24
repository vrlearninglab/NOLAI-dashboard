<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Placeholder;

class PlaceholderController extends Controller
{
    public function storeData(Request $request)
    {
        $validated = $request->validate([
            'dummy_data' => 'required|string|max:255',
        ]);
        $placeholder = new Placeholder();
        $placeholder->dummy_data = $request->input('dummy_data');
        $placeholder->save();
        return response()->json(['message' => 'Data succesvol opgeslagen']);
    }
}
