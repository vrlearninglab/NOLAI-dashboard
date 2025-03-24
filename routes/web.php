<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\PlaceholderController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StreamController;

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
    return view('start');
});

Route::post('/store-name', [UserController::class, 'store'])->name('store.name');
Route::get('/home/{name}', [UserController::class, 'home'])->name('home');

Route::get('/start-sessie', [StudentController::class, 'showStudents'])->name('start.sessie');
Route::post('/start-sessie', [ProcessController::class, 'newSession']);

Route::get('/select-session', [SessionController::class, 'index'])->name('sessions.index');
Route::get('/api/sessions', [SessionController::class, 'getSessions'])->name('sessions.list');

Route::get('/sessie', [StreamController::class, 'getStream'])->name('sessie');

Route::get('/sessie-analyse/{id}', [SessionController::class, 'show'])->name('sessie-analyse');

Route::get('/notes/{sessionId?}', [NoteController::class, 'index'])->name('notes.index');
Route::post('/notes/{sessionId?}', [NoteController::class, 'store'])->name('notes.store');

Route::post('/send-to-unity', function (Illuminate\Http\Request $request) {
    $message = $request->input('message', '');

    Cache::put('unity_message', $message, now()->addSeconds(10));
});


Route::get('/get-message', function () {
    return Cache::pull('unity_message', '');
});

Route::post('/store-data', [PlaceholderController::class, 'storeData']);
Route::post('/store-image', [ImageController::class, 'store']);
Route::post('/store-image-batch', [ImageController::class, 'storeBatch']);

Route::get('/recording', [ImageController::class, 'showRecording']);
Route::get('/get-images/{sessionId}', [ImageController::class, 'getImages']);

Route::post('/upload-audio', [AudioController::class, 'uploadAudio']);

Route::post('/update-stream', [StreamController::class, 'updateStream']);


Route::get('/laravel', function () {
    return view('welcome');
});