<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MovingProduct
 *
 * @property int $id
 * @property int $moving_id
 * @property int $product_id
 * @property string $count
 * @property string|null $old_price
 * @property string $price
 * @property string $all_price
 * @property string|null $storage_life
 * @property string|null $comment
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Moving $moving
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereAllPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereMovingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereOldPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereStorageLife($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MovingProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MovingProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'deleted_at',
        'created_at',
        'updated_at',
        'id',
        'comment',
        'all_price',
        'count',
        'price',
        'old_price',
        'storage_life',
        'moving_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function moving(): BelongsTo
    {
        return $this->belongsTo(Moving::class);
    }
}
