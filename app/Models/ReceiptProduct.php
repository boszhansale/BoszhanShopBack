<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ReceiptProduct
 *
 * @property int $id
 * @property int $receipt_id
 * @property int $product_id
 * @property string $count
 * @property string $price
 * @property string $all_price
 * @property string|null $comment
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereAllPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereUpdatedAt($value)
 * @property string|null $old_price
 * @property string|null $storage_life
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Receipt $receipt
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereOldPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptProduct whereStorageLife($value)
 * @mixin \Eloquent
 */
class ReceiptProduct extends Model
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
        'receipt_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }
}
