<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Menu
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereFontIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process wherePriorityView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Wallet\Entities\Process whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Process extends Model
{
    protected $fillable = [
        "rule_id",
        "class",
        "methods",
        "params",
    ];

    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }
}
