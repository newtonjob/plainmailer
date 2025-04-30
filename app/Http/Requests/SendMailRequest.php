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
            'from'        => 'required|string',
            'subject'     => 'required|string',
            'to'          => 'required|array',
            'reply_to'    => 'array',
            'bcc'         => 'array',
            'cc'          => 'array',
            'html'        => 'required',
            'text'        => 'string',
            'attachments' => 'array',
            'config'      => 'array',
        ];
    }

    public function mailer(): Mailer
    {
        return $this->filled('config') ? Mail::build($this->config) : Mail::mailer();
    }
}
