<?php

namespace App\Actions;

use App\Data\SendContactData;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class SentContact
{
    public function execute(SendContactData $data): void
    {
        $to = 'hubertalvam@gmail.com';

        Mail::to($to)->queue(new Contact($data));
    }
}
