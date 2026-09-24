<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Events\Events\EventResource;
use App\Models\Events\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin/events')->assertRedirect('/admin/login');
    }

    public function test_hosts_can_access_the_events_list(): void
    {
        $host = User::factory()->create(['is_admin' => false]);

        $this->actingAs($host)
            ->get('/admin/events')
            ->assertOk();
    }

    public function test_hosts_cannot_access_the_catalog(): void
    {
        $host = User::factory()->create(['is_admin' => false]);

        $this->actingAs($host)
            ->get('/admin/catalog')
            ->assertForbidden();
    }

    public function test_admins_can_access_the_catalog(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin/catalog')
            ->assertOk();
    }

    public function test_hosts_only_see_their_own_events(): void
    {
        $host = User::factory()->create(['is_admin' => false]);
        $otherHost = User::factory()->create(['is_admin' => false]);

        Event::factory()->create(['user_id' => $host->id]);
        Event::factory()->create(['user_id' => $otherHost->id]);

        $this->actingAs($host);

        $this->assertSame(1, EventResource::getEloquentQuery()->count());
    }

    public function test_admins_see_every_event(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $host = User::factory()->create(['is_admin' => false]);
        $otherHost = User::factory()->create(['is_admin' => false]);

        Event::factory()->create(['user_id' => $host->id]);
        Event::factory()->create(['user_id' => $otherHost->id]);

        $this->actingAs($admin);

        $this->assertSame(2, EventResource::getEloquentQuery()->count());
    }
}
