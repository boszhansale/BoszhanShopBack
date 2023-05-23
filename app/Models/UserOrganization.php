<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserOrganization
 *
 * @property int $id
 * @property int $user_id
 * @property int $organization_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrganization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrganization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrganization query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrganization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrganization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrganization whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrganization whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserOrganization whereUserId($value)
 * @mixin \Eloquent
 */
class UserOrganization extends Model
{
    use HasFactory;
}
