<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Events\Events\Pages\CreateEvent;
use App\Models\Events\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EventLocationFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_address_is_required_to_create_an_event(): void
    {
        $host = User::factory()->create(['is_admin' => false]);

        $this->actingAs($host);

        Livewire::test(CreateEvent::class)
            ->fillForm([
                'type' => 'aniversario',
                'title' => 'Festa da Maria',
                'address' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['address' => 'required']);

        $this->assertSame(0, Event::count());
    }

    public function test_latitude_requires_longitude_and_vice_versa(): void
    {
        $host = User::factory()->create(['is_admin' => false]);

        $this->actingAs($host);

        Livewire::test(CreateEvent::class)
            ->fillForm([
                'type' => 'aniversario',
                'title' => 'Festa da Maria',
                'address' => 'Rua das Flores, 123',
                'latitude' => -23.5613,
                'longitude' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['longitude' => 'required_with']);
    }

    public function test_an_event_can_be_created_with_a_full_location(): void
    {
        $host = User::factory()->create(['is_admin' => false]);

        $this->actingAs($host);

        Livewire::test(CreateEvent::class)
            ->fillForm([
                'type' => 'aniversario',
                'title' => 'Festa da Maria',
                'address' => 'Rua das Flores, 123, São Paulo - SP',
                'latitude' => -23.5613,
                'longitude' => -46.6565,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $event = Event::first();

        $this->assertSame('Rua das Flores, 123, São Paulo - SP', $event->address);
        $this->assertEqualsWithDelta(-23.5613, $event->latitude, 0.0001);
        $this->assertEqualsWithDelta(-46.6565, $event->longitude, 0.0001);
    }
}
