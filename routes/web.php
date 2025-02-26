<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\PlaceholderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::post('/send-to-unity', function (Illuminate\Http\Request $request) {
    $message = $request->input('message', '');

    Cache::put('unity_message', $message, now()->addSeconds(10));

    return response()->json(['message' => 'Bericht opgeslagen voor Unity!']);
});


Route::get('/get-message', function () {
    return Cache::pull('unity_message', '');
});

Route::post('/store-data', [PlaceholderController::class, 'storeData']);

Route::get('/laravel', function () {
    return view('welcome');
});