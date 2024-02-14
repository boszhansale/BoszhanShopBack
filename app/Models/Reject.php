<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;


/**
 * App\Models\Reject
 *
 * @property int $id
 * @property int $status
 * @property int $payment_type
 * @property int $payment_status
 * @property int $user_id
 * @property int|null $counteragent_id
 * @property int|null $store_id
 * @property int|null $storage_id
 * @property int|null $organization_id
 * @property string|null $total_price
 * @property string|null $removed_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ReasonRefund $ReasonRefund
 * @property-read \App\Models\Counteragent|null $counteragent
 * @property-read \App\Models\Organization|null $organization
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RefundProduct> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Reject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reject query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereCounteragentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereRemovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereUserId($value)
 * @property-read \App\Models\Store|null $store
 * @property int $source
 * @property int|null $inventory_id
 * @property int|null $moving_id
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereInventoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereMovingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reject whereSource($value)
 * @mixin \Eloquent
 */

//Списание
class Reject extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
    ];

    protected $fillable = [
        'id',
        'removed_at',
        'updated_at',
        'counteragent_id',
        'created_at',
        'deleted_at',
        'storage_id',
        'organization_id',
        'store_id',
        'total_price',
        'user_id',
        'status',
        'inventory_id',
        'description',
        'moving_id',
        'source'
    ];

    const  SOURCE = [
        1 => 'списание',
        2 => 'перемещение на склад'
    ];

    public function counteragent(): BelongsTo
    {
        return $this->belongsTo(Counteragent::class);
    }
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function ReasonRefund(): BelongsTo
    {
        return $this->belongsTo(ReasonRefund::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function products(): HasMany
    {
        return $this->hasMany(RejectProduct::class);
    }
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('d.m.Y H:i'),
        );
    }
    public function getDate():string
    {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }
}
