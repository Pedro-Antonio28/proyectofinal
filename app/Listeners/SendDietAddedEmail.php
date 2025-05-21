<?php

namespace App\Listeners;

use App\Events\PostAddedToDiet;
use App\Mail\DietAddedMail;
use Illuminate\Support\Facades\Mail;

class SendDietAddedEmail
{
    public function handle(PostAddedToDiet $event)
    {
        Mail::to($event->post->user->email)->send(new DietAddedMail($event->user, $event->post));
    }
}
