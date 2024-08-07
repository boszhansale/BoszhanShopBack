<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\InventoryProduct
 *
 * @property int $id
 * @property int $inventory_id
 * @property int $product_id
 * @property string $receipt поступления
 * @property string $sale продажа
 * @property string $moving перемещение
 * @property string $remains остатки
 * @property string $count Фактическое количество
 * @property string $overage Излишки
 * @property string $shortage Недостачи
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereInventoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereMoving($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereOverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereRemains($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereShortage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereUpdatedAt($value)
 * @property string $moving_from перемещение
 * @property string $moving_to
 * @property string $overage_price
 * @property string $shortage_price
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereMovingFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereMovingTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereOveragePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryProduct whereShortagePrice($value)
 * @mixin \Eloquent
 */
class InventoryProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'receipt',
        'sale',
        'moving_from',
        'moving_to',
        'remains',
        'count',
        'overage',
        'overage_price',
        'shortage',
        'shortage_price',
        'price',

        'between_sale',
        'between_receipt',
        'between_moving_from',
        'between_moving_to',
    ];
    protected $hidden = ['created_at','updated_at'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
