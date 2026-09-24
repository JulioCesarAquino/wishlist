<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Identity\Users\Pages\CreateUser;
use App\Filament\Resources\Identity\Users\Pages\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_hosts_cannot_access_the_users_list(): void
    {
        $host = User::factory()->create(['is_admin' => false]);

        $this->actingAs($host)
            ->get('/admin/users')
            ->assertForbidden();
    }

    public function test_admins_can_access_the_users_list(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertOk();
    }

    public function test_admins_can_create_a_host_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Novo Anfitrião',
                'email' => 'anfitriao@example.com',
                'password' => 'senha-segura-123',
                'is_admin' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $host = User::where('email', 'anfitriao@example.com')->first();

        $this->assertNotNull($host);
        $this->assertFalse($host->is_admin);
        $this->assertNotNull($host->email_verified_at);
        $this->assertTrue(Hash::check('senha-segura-123', $host->password));
    }

    public function test_admins_can_create_another_admin_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Novo Admin',
                'email' => 'novo-admin@example.com',
                'password' => 'senha-segura-123',
                'is_admin' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $newAdmin = User::where('email', 'novo-admin@example.com')->first();

        $this->assertNotNull($newAdmin);
        $this->assertTrue($newAdmin->is_admin);
    }

    public function test_admins_can_promote_a_host_to_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $host = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $host->getRouteKey()])
            ->fillForm(['is_admin' => true])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue($host->fresh()->is_admin);
    }

    public function test_admins_can_demote_an_admin_to_host(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $otherAdmin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(EditUser::class, ['record' => $otherAdmin->getRouteKey()])
            ->fillForm(['is_admin' => false])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertFalse($otherAdmin->fresh()->is_admin);
    }
}
