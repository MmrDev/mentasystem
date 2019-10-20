<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Menu
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereFontIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule wherePriorityView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Rule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rule extends Model
{
    protected $fillable = [
        "campaign_id",
        "params",
        "operators",
        "values",
        "revoked",
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function processes()
    {
        return $this->hasMany(Process::class, "rule_id");
    }
}
