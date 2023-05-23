<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserStorage
 *
 * @property int $id
 * @property int $user_id
 * @property int $storage_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserStorage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStorage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStorage query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStorage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStorage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStorage whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStorage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStorage whereUserId($value)
 * @mixin \Eloquent
 */
class UserStorage extends Model
{
    use HasFactory;
}
