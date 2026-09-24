<?php

namespace App\Models\Catalog;

use Database\Factories\Catalog\ProductTemplateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $category
 * @property string|null $description
 * @property string|null $image
 * @property float|null $suggested_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'category', 'description', 'image', 'suggested_price'])]
class ProductTemplate extends Model
{
    /** @use HasFactory<ProductTemplateFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'suggested_price' => 'decimal:2',
        ];
    }

    /**
     * @return HasMany<EventProduct, $this>
     */
    public function eventProducts(): HasMany
    {
        return $this->hasMany(EventProduct::class);
    }
}
