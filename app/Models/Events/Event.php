<?php

namespace App\Models\Events;

use App\Models\Catalog\EventProduct;
use App\Models\Guests\Guest;
use App\Models\Orders\Order;
use App\Models\User;
use Database\Factories\Events\EventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property int $user_id
 * @property string $slug
 * @property string $type
 * @property string $title
 * @property Carbon|null $event_date
 * @property string|null $cover_image
 * @property array<int, string>|null $gallery
 * @property string|null $description
 * @property string|null $story
 * @property string|null $mp_access_token
 * @property string|null $mp_public_key
 * @property bool $is_published
 * @property bool $is_premium
 * @property int $visits_count
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string|null $font_color_primary
 * @property string|null $font_color_secondary
 * @property string|null $font_family
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'user_id', 'slug', 'type', 'title', 'event_date', 'cover_image',
    'gallery', 'description', 'story', 'mp_access_token', 'mp_public_key', 'is_published', 'is_premium',
    'primary_color', 'secondary_color', 'font_color_primary', 'font_color_secondary', 'font_family',
])]
#[Hidden(['mp_access_token', 'mp_public_key'])]
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    use LogsActivity;

    /**
     * `is_premium` is hidden from the host's form and defaults at the
     * database level, so a new event's in-memory attribute would otherwise
     * stay unset (null) until refreshed from the database — causing the
     * activity log to record a false "changed from null to false" on the
     * very next update. Seeding it here keeps the in-memory value in sync
     * with the column default from the moment the model is instantiated.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_premium' => false,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('event')
            ->logOnly([
                'title', 'type', 'event_date', 'description', 'story', 'cover_image', 'gallery',
                'primary_color', 'secondary_color', 'font_color_primary', 'font_color_secondary', 'font_family',
                'is_published', 'is_premium',
            ])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $event) => match ($event) {
                'created' => 'criou o evento',
                'updated' => 'atualizou o evento',
                'deleted' => 'excluiu o evento',
                default => $event,
            });
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event): void {
            if (blank($event->slug)) {
                $event->slug = $event->generateUniqueSlug($event->title);
            }
        });
    }

    protected function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $suffix = 1;

        while (static::where('slug', $slug)->exists()) {
            $suffix++;
            $slug = "{$base}-{$suffix}";
        }

        return $slug;
    }

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'gallery' => 'array',
            'is_published' => 'boolean',
            'is_premium' => 'boolean',
            'mp_access_token' => 'encrypted',
            'mp_public_key' => 'encrypted',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<EventProduct, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(EventProduct::class);
    }

    /**
     * @return HasMany<Guest, $this>
     */
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function coverImageUrl(): ?string
    {
        return $this->cover_image ? Storage::disk('public')->url($this->cover_image) : null;
    }

    /**
     * @return array<int, string>
     */
    public function galleryUrls(): array
    {
        return array_map(
            fn (string $path): string => Storage::disk('public')->url($path),
            $this->gallery ?? [],
        );
    }

    public function isViewableBy(?User $user): bool
    {
        if ($this->is_published) {
            return true;
        }

        return $user && ($user->isAdmin() || $user->id === $this->user_id);
    }

    public function visitCookieName(): string
    {
        return "wishlist_visited_{$this->id}";
    }

    public function shouldCountVisitFor(?User $user): bool
    {
        if (! $this->is_published) {
            return false;
        }

        return ! ($user && ($user->isAdmin() || $user->id === $this->user_id));
    }
}
