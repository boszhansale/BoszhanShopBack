<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserStore
 *
 * @property int $id
 * @property int $user_id
 * @property int $store_id
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
}
