<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreStorage
 *
 * @property int $id
 * @property int $store_id
 * @property int $storage_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStorage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStorage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStorage query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStorage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStorage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStorage whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStorage whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStorage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreStorage extends Model
{
    use HasFactory;
}
