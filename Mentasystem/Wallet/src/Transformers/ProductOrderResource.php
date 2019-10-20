<?php

namespace Modules\Wallet\Transformers;

use App\repo\UserDB;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Product\Transformers\CostResource;

class ProductOrderResource extends Resource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        $userDB = new UserDB();
        $from = $userDB->find($this->from_user_id);
        $to = $userDB->find($this->to_user_id);
        return [
            "id" => $this->id,
            "from_user_id" => $from->name,
            "to_user_id" => $to->name,
            "paid_at" => $this->paid_at,
//            "revoked"=> 0,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "costs" => $this->when(isset($this->costs), function () {
                return CostResource::collection($this->costs);
            }),
            /*            "wallet_order" => $this->when(isset($this->orders), function () {
                            return OrderResource::collection($this->orders);
                        }),*/
        ];
    }

}
