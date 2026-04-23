<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MsoTransaction;

class MsoTransactionPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['Admin','Supervisor','Operator','Viewer']);
    }

    public function view(User $user, MsoTransaction $mso)
    {
        return $user->hasAnyRole(['Admin','Supervisor','Operator','Viewer']);
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['Admin','Supervisor','Operator']);
    }

    public function update(User $user, MsoTransaction $mso)
    {
        return $user->hasAnyRole(['Admin','Supervisor']);
    }

    public function delete(User $user, MsoTransaction $mso)
    {
        return $user->hasRole('Admin');
    }
}
