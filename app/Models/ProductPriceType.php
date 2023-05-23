<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ProductPriceType
 *
 * @property int $id
 * @property float $price
 * @property int $price_type_id
 * @property int $product_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType wherePriceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPriceType whereUpdatedAt($value)
 * @property-read \App\Models\PriceType $priceType
 * @mixin \Eloquent
 */
class ProductPriceType extends Model
{
    use HasFactory;
    protected $fillable = ['price', 'price_type_id', 'product_id','id','deleted_at'];


    public function priceType(): BelongsTo
    {
        return $this->belongsTo(PriceType::class);
    }
}
