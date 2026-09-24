<?php

namespace App\Policies\Identity;

use App\Models\Identity\InviteRequest;
use App\Models\User;

class InviteRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, InviteRequest $inviteRequest): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, InviteRequest $inviteRequest): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, InviteRequest $inviteRequest): bool
    {
        return $user->isAdmin();
    }
}
