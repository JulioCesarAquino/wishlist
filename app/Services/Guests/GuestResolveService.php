<?php

namespace App\Services\Guests;

use App\Models\Events\Event;
use App\Models\Guests\Guest;

class GuestResolveService
{
    /**
     * Find the guest for this event, updating their contact details, or
     * create a new one if none matches.
     *
     * Resolution first tries the browser cookie identifier. That fails
     * whenever the same person visits from a different browser/device, so
     * we fall back to matching an existing guest of this same event by
     * WhatsApp number (digits only, ignoring formatting) — the one piece of
     * contact info we always require — instead of fragmenting their gift
     * and RSVP history across duplicate guest rows.
     *
     * @param  array{name: string, whatsapp: string, email?: ?string}  $guestData
     */
    public function execute(Event $event, array $guestData, ?string $guestIdentifier): Guest
    {
        $guest = $guestIdentifier
            ? $event->guests()->where('identifier', $guestIdentifier)->first()
            : null;

        $guest ??= $this->findByWhatsapp($event, $guestData['whatsapp']);

        if ($guest) {
            $guest->update($guestData);

            return $guest;
        }

        return $event->guests()->create($guestData);
    }

    private function findByWhatsapp(Event $event, string $whatsapp): ?Guest
    {
        $normalized = preg_replace('/\D+/', '', $whatsapp);

        if (blank($normalized)) {
            return null;
        }

        // Compared in PHP (not a DB-side regex) so this stays portable across
        // the MySQL connection used in production and the SQLite one used in
        // tests, and because per-event guest lists are small enough that
        // loading them isn't a real cost.
        return $event->guests()
            ->get()
            ->first(fn (Guest $guest) => preg_replace('/\D+/', '', $guest->whatsapp) === $normalized);
    }
}
