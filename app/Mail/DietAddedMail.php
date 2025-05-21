<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class DietAddedMail extends Mailable
{
use Queueable;

public function __construct(public User $user, public Post $post) {}

public function build()
{
return $this->subject('Tu dieta ha sido añadida')
->view('emails.diet-added');
}
}
