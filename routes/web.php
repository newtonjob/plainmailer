<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/up');

Route::post('/emails', function (Request $request) {
    Mail::macro('forward', fn ($message) => $this->sendSymfonyMessage($message));

    Mail::build($request->config)->forward(unserialize($request->message));
});
