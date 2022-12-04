<?php

namespace App\Http\Controllers;

use App\Mail\AbstractMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ShootEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['from.address' => 'required', 'to' => 'required', 'html' => 'required']);

        Mail::send(new AbstractMail($request));

        return response(['message' => 'Sent successfully']);
    }
}
