<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductBarcode
 *
 * @property int $id
 * @property int $product_id
 * @property string $barcode
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBarcode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBarcode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBarcode query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBarcode whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBarcode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBarcode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBarcode whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductBarcode whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductBarcode extends Model
{
    use HasFactory;

    protected $fillable = ['id','product_id','barcode'];
}
