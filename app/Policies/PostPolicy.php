<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, Post $post): Response
    {
        return $user->id === $post->user_id
                    ? Response::allow()
                    : Response::deny('DENIED: Require Delete Policy.');
    }
}
