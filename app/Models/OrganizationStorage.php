<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrganizationStorage
 *
 * @property int $id
 * @property int $organization_id
 * @property int $storage_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationStorage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationStorage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationStorage query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationStorage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationStorage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationStorage whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationStorage whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationStorage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganizationStorage extends Model
{
    use HasFactory;
}
