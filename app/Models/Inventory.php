<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Inventory
 *
 * @property int $id
 * @property int $user_id
 * @property int $store_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory query()
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryProduct> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Store $store
 * @mixin \Eloquent
 */
class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['store_id','id','created_at','user_id'];

    public function products(): HasMany
    {
        return $this->hasMany(InventoryProduct::class);
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
