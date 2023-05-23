<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Counteragent
 *
 * @property int $id
 * @property string $name
 * @property string|null $id_1c
 * @property string|null $bin
 * @property string|null $iik
 * @property string|null $bik
 * @property int $payment_type
 * @property int $price_type_id
 * @property float $discount
 * @property int $enabled
 * @property string|null $delivery_time
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereBik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereBin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereId1c($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereIik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent wherePriceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counteragent whereUpdatedAt($value)
 * @property-read \App\Models\PriceType $priceType
 * @mixin \Eloquent
 */
class Counteragent extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'group_id',
        'id_1c',
        'bin',
        'iik',
        'bik',
        'payment_type',
        'price_type_id',
        'discount',
        'enabled',
        'created_at',
        'deleted_at'
    ];

    public function priceType(): BelongsTo
    {
        return $this->belongsTo(PriceType::class);
    }
}
