<?php

use App\Http\Requests\SendMailRequest;
use App\Mail\AbstractMail;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/up');

Route::post('/send', function (SendMailRequest $request) {
    $request->mailer()->send(new AbstractMail($request->safe()));

    return response()->noContent();
});
