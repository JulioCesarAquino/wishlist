<?php

namespace App\Filament\Resources\Identity\Users\Pages;

use App\Filament\Resources\Identity\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected bool $isAdmin = false;

    /**
     * `is_admin` isn't mass-assignable (it's a privilege, not a regular
     * profile field), so it's pulled out here and applied afterCreate()
     * via forceFill() instead of going through the guarded create().
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->isAdmin = (bool) ($data['is_admin'] ?? false);

        unset($data['is_admin']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->forceFill([
            'email_verified_at' => now(),
            'is_admin' => $this->isAdmin,
        ])->save();
    }
}
