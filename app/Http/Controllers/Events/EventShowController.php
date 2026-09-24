<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use App\Models\Guests\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;

class EventShowController extends Controller
{
    public function __invoke(Request $request, Event $event): Response
    {
        abort_unless($event->isViewableBy($request->user()), 404);

        $guest = null;
        $identifier = $request->cookie(Guest::cookieName($event));

        if ($identifier) {
            $guest = $event->guests()->where('identifier', $identifier)->first();
        }

        if ($event->shouldCountVisitFor($request->user()) && ! $request->cookie($event->visitCookieName())) {
            $event->increment('visits_count');
            Cookie::queue(Cookie::make($event->visitCookieName(), '1', 60 * 12));
        }

        return Inertia::render('events/show', [
            'event' => [
                'slug' => $event->slug,
                'type' => $event->type,
                'title' => $event->title,
                'event_date' => $event->event_date?->toDateString(),
                'description' => $event->description,
                'cover_image_url' => $event->coverImageUrl(),
                'gallery_urls' => $event->galleryUrls(),
                'story' => $event->story,
                'address' => $event->address,
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'primary_color' => $event->primary_color,
                'secondary_color' => $event->secondary_color,
                'font_color_primary' => $event->font_color_primary,
                'font_color_secondary' => $event->font_color_secondary,
                'font_family' => $event->font_family,
                'cover_effect_intensity' => $event->cover_effect_intensity,
                'is_published' => $event->is_published,
                'visits_count' => $event->visits_count,
                'mp_public_key' => $event->mp_public_key,
            ],
            'is_preview' => ! $event->is_published,
            'products' => $event->products()
                ->where('is_active', true)
                ->get()
                ->map(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'image_url' => $product->imageUrl(),
                    'price' => (float) $product->price,
                    'quantity_available' => $product->quantityAvailable(),
                    'is_sold_out' => $product->isSoldOut(),
                ]),
            'guest' => $guest ? [
                'name' => $guest->name,
                'whatsapp' => $guest->whatsapp,
                'email' => $guest->email,
                'rsvp_status' => $guest->rsvp_status,
                'rsvp_guests_count' => $guest->rsvp_guests_count,
            ] : null,
        ]);
    }
}
