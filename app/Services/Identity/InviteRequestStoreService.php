<?php

namespace App\Services\Identity;

use App\Models\Identity\InviteRequest;

class InviteRequestStoreService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): InviteRequest
    {
        return InviteRequest::create($data);
    }
}
