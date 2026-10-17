<?php

namespace App\Actions;

use App\Data\ContactData;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;

class SendContactMessage
{
    public function execute(ContactData $data): void
    {
        Mail::to(config('mail.to.address'), config('mail.to.name'))
            ->queue(new ContactMessage($data));
    }
}
