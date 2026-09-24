<?php

namespace App\Models\Guests;

use App\Models\Events\Event;
use App\Models\Orders\Order;
use Database\Factories\Guests\GuestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $event_id
 * @property string $identifier
 * @property string $name
 * @property string $whatsapp
 * @property string|null $email
 * @property string|null $rsvp_status
 * @property int|null $rsvp_guests_count
 * @property Carbon|null $rsvp_responded_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['event_id', 'name', 'whatsapp', 'email', 'rsvp_status', 'rsvp_guests_count', 'rsvp_responded_at'])]
class Guest extends Model
{
    /** @use HasFactory<GuestFactory> */
    use HasFactory;

    public const RSVP_CONFIRMED = 'confirmed';

    public const RSVP_DECLINED = 'declined';

    protected static function booted(): void
    {
        static::creating(function (Guest $guest): void {
            $guest->identifier ??= (string) Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'rsvp_guests_count' => 'integer',
            'rsvp_responded_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public static function cookieName(Event $event): string
    {
        return "wishlist_guest_{$event->id}";
    }
}
