<?php

namespace App\Services\Identity;

use App\Models\Identity\InviteRequest;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class InviteRequestApproveService
{
    public function execute(InviteRequest $inviteRequest): string
    {
        $user = User::create([
            'name' => $inviteRequest->name,
            'email' => $inviteRequest->email,
            'password' => Str::random(40),
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        $inviteRequest->forceFill([
            'status' => InviteRequest::STATUS_APPROVED,
            'invited_user_id' => $user->id,
            'approved_at' => now(),
        ])->save();

        return Filament::getResetPasswordUrl(
            Password::broker()->createToken($user),
            $user,
        );
    }
}
