<?php

namespace App\Observers;

use App\Models\Counter;
use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        $user->index = Counter::nextId(User::class);
        $user->ID_validate = -1;
    }
}
