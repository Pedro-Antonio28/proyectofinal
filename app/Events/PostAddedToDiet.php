<?php

namespace App\Events;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class PostAddedToDiet
{
    use Dispatchable;

    public function __construct(public User $user, public Post $post) {}
}
