<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Valideer de naam
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Controleer of de naam al bestaat
        $user = User::where('name', $request->name)->first();

        if (!$user) {
            // Als de naam niet bestaat, slaan we deze op
            $user = User::create([
                'name' => $request->name,
            ]);
        }

        // Stuur de gebruiker door naar de homepagina met de naam in de route
        return redirect()->route('home', ['name' => $user->name]);
    }

    public function home($name)
    {
        // Toon de homepagina met de naam van de gebruiker
        return view('home', compact('name'));
    }
}
