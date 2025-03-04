<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\PlaceholderController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AudioController;

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

Route::get('/session', function () {
    return view('session');
});

Route::post('/send-to-unity', function (Illuminate\Http\Request $request) {
    $message = $request->input('message', '');

    Cache::put('unity_message', $message, now()->addSeconds(10));
});


Route::get('/get-message', function () {
    return Cache::pull('unity_message', '');
});

Route::post('/store-data', [PlaceholderController::class, 'storeData']);
Route::post('/store-image', [ImageController::class, 'store']);

Route::get('/recording', [ImageController::class, 'showRecording']);
Route::get('/get-images', [ImageController::class, 'getImages']);

Route::post('/upload-audio', [AudioController::class, 'uploadAudio']);

Route::get('/laravel', function () {
    return view('welcome');
});