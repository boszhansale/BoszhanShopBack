<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UserStore
 *
 * @property int $id
 * @property int $user_id
 * @property int $store_id
 * @property string|null $webkassa_login
 * @property string|null $webkassa_token
 * @property string|null $webkassa_login_at
 * @property string|null $webkassa_password
 * @property int|null $webkassa_cash_box_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserStore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStore query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStore whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStore whereUserId($value)
 * @mixin \Eloquent
 */
class UserStore extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "store_id",
        'webkassa_login',
        'webkassa_token',
        'webkassa_password',
        'webkassa_login_at',
        'webkassa_cash_box_id'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
