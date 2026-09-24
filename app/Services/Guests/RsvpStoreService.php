<?php

namespace App\Services\Guests;

use App\Models\Events\Event;
use App\Models\Guests\Guest;

class RsvpStoreService
{
    public function __construct(
        protected GuestResolveService $guestResolveService,
    ) {}

    /**
     * @param  array{name: string, whatsapp: string, email: ?string}  $guestData
     */
    public function execute(
        Event $event,
        array $guestData,
        bool $attending,
        ?int $guestsCount,
        ?string $guestIdentifier,
    ): Guest {
        $guest = $this->guestResolveService->execute($event, $guestData, $guestIdentifier);

        $guest->forceFill([
            'rsvp_status' => $attending ? Guest::RSVP_CONFIRMED : Guest::RSVP_DECLINED,
            'rsvp_guests_count' => $attending ? $guestsCount : null,
            'rsvp_responded_at' => now(),
        ])->save();

        return $guest;
    }
}
