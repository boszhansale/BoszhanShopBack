<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreProductDiscount
 *
 * @property int $id
 * @property int $store_id
 * @property int $product_id
 * @property float $discount
 * @property string $price
 * @property string|null $date_from
 * @property string|null $date_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductDiscount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreProductDiscount extends Model
{
    use HasFactory;
}
