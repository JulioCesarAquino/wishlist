<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Audit\ActivityLogs\ActivityLogResource;
use App\Models\Events\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin/activity-logs')->assertRedirect('/admin/login');
    }

    public function test_hosts_cannot_access_the_activity_log(): void
    {
        $host = User::factory()->create(['is_admin' => false]);

        $this->actingAs($host)
            ->get('/admin/activity-logs')
            ->assertForbidden();
    }

    public function test_admins_can_access_the_activity_log(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin/activity-logs')
            ->assertOk();
    }

    public function test_admins_see_activity_for_every_event(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $host = User::factory()->create(['is_admin' => false]);

        $this->actingAs($host);
        $event = Event::factory()->create(['user_id' => $host->id]);
        $event->update(['title' => 'Evento Do Host']);

        $this->actingAs($admin);

        $this->assertTrue(ActivityLogResource::getEloquentQuery()->where('subject_type', Event::class)->where('subject_id', $event->id)->exists());
    }
}
