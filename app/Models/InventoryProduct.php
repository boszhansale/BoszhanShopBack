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
 * @mixin \Eloquent
 */
class InventoryProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'count',
        'moving',
        'overage',
        'receipt',
        'inventory_id',
        'remains',
        'sale',
        'shortage'
    ];
    protected $hidden = ['created_at','updated_at'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
