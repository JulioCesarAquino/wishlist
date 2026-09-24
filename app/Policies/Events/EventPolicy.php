<?php

namespace App\Policies\Events;

use App\Models\Events\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Event $event): bool
    {
        return $user->isAdmin() || $user->id === $event->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Event $event): bool
    {
        return $user->isAdmin() || $user->id === $event->user_id;
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->isAdmin() || $user->id === $event->user_id;
    }
}
