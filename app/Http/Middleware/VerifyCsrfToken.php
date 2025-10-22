<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/send-to-unity',
        '/get-message',
        '/store-data',
        '/store-image',
        '/upload-audio',
        '/upload-microphone-audio',
        '/store-image-batch',
        '/update-stream',
        '/trigger-buttons',
        '/set-unity-status',
    ];
}
