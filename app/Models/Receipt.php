<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Receipt
 *
 * @property int $id
 * @property int $status
 * @property int $operation
 * @property string|null $bank
 * @property int $user_id
 * @property int|null $counteragent_id
 * @property int|null $store_id
 * @property int|null $storage_id
 * @property int|null $organization_id
 * @property string $total_price
 * @property string|null $removed_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereCounteragentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereRemovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereUserId($value)
 * @property-read \App\Models\Counteragent|null $counteragent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReceiptProduct> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Storage|null $storage
 * @property-read \App\Models\Store|null $store
 * @property int $check_status
 * @property int|null $source
 * @property int|null $inventory_id
 * @property int|null $moving_id
 * @property int|null $refund_id
 * @property string|null $description
 * @property int $nds
 * @property string|null $ticket_print_url
 * @property string|null $check_number
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereCheckNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereCheckStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereInventoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereMovingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereNds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereRefundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereTicketPrintUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt withoutTrashed()
 * @mixin \Eloquent
 */
class Receipt extends Model
{
    use HasFactory,SoftDeletes;
    public $timestamps = false;
    protected $casts =  [
        'created_at' => 'datetime:d.m.Y H:i',
    ];
    protected $fillable = [
        'id',
        'organization_id',
        'store_id',
        'status',
        'storage_id',
        'user_id',
        'total_price',
        'counteragent_id',
        'removed_at',
        'operation',
        'inventory_id',
        'description',
        'source',
        'moving_id',
        'refund_id',
        'nds',
        'check_number',
        'ticket_print_url',
        'check_status',
    ];
    //1. Новый документ поступления товара 2. Журнал документов поступления товара

    const OPERATION = [
       1 => 'Поступление товара',
       2 => 'Оприходование излишков'
    ];

    const SOURCE = [
      1 => 'поступления от поставщика',
      2 => 'перемещение с склада',
      3 => 'возврат от клиента',
      4 => 'излишки',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(ReceiptProduct::class);
    }
    public function counteragent(): BelongsTo
    {
        return $this->belongsTo(Counteragent::class);
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
