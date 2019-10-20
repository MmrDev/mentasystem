<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Menu
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereFontIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount wherePriorityView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\ClubAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClubAccount extends Model
{
    protected $fillable = [
        "club_id",
        "account_id",
        "type",
        "expired_at",
        "revoked",
    ];

}
