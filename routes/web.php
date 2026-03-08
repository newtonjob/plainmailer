<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/up');

Route::post('/emails', function (Request $request) {
    Mail::macro('forward', fn ($message) => $this->sendSymfonyMessage($message));

    Mail::build($request->config)->forward(unserialize(base64_decode($request->message)));
})->withoutMiddleware(VerifyCsrfToken::class);
