<?php

namespace App\Policies;

use App\Models\GroupTour;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupTourPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, GroupTour $groupTour): Response
    {
        return $user->id === $groupTour->user_id
                    ? Response::allow()
                    : Response::deny('DENIED: Require Delete Group Tour Policy.');
    }
}
