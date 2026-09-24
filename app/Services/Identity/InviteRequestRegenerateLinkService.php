<?php

namespace App\Services\Identity;

use App\Models\Identity\InviteRequest;
use Illuminate\Support\Facades\Password;

class InviteRequestRegenerateLinkService
{
    public function execute(InviteRequest $inviteRequest): string
    {
        $user = $inviteRequest->invitedUser()->firstOrFail();

        return route('password.reset', [
            'token' => Password::broker()->createToken($user),
            'email' => $user->email,
        ]);
    }
}
