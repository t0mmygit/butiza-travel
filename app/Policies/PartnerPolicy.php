<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PartnerPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Partner $partner): Response
    {
        return $user->id === $partner->user_id
                    ? Response::allow()
                    : Response::deny('DENIED: Require Update Partner Policy');
    }
}
