<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Mail;

class SendMailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'from.address' => 'required',
            'from.name'    => 'required',
            'to'           => 'required',
            'html'         => 'required',
            'text'         => 'string',
            'config'       => 'array',
        ];
    }

    public function mailer(): Mailer
    {
        return $this->filled('config') ? Mail::build($this->config) : Mail::mailer();
    }
}
