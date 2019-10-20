<?php

namespace Modules\Wallet\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\Goods;
use Modules\Wallet\Transformers\GoodsResource;

class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $limit = \request()->has("limit") ? \request()->input("limit") : 5;
        $goods = Goods::paginate($limit);
        $resource = GoodsResource::collection($goods);
        return $resource;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $instance = Goods::create($data);
        if ($instance instanceof Goods) {
            return \response()
                ->json([
                    "message" => "goods successfully added",
                    "data" => $instance
                ], 200);
        }
        return \response()
            ->json([
                "message" => "some thing went wrong",
                "error" => "bad request"
            ], 400);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return mixed
     */
    public function show($id)
    {
        $goods = Goods::find($id);
        $resource = new GoodsResource($goods);
        return $resource;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return null
     */
    public function destroy($id)
    {
        $goods = Goods::find($id);
        $goods->delete();
        return \response()
            ->json([
                null
            ], 204);
    }
}
