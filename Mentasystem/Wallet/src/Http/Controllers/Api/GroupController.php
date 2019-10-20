<?php

namespace Modules\Account\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\Group;
use Modules\Account\Transformers\GroupResource;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $limit = \request()->has("limit") ? \request()->input("limit") : 5;
        $groups = Group::paginate($limit);
        $resource = GroupResource::collection($groups);
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
        $instance = Group::create($data);
        if ($instance instanceof Group) {
            return \response()
                ->json([
                    "message" => "group successfully created",
                    "data" => $instance
                ], 200);
        }
        return \response()
            ->json([
                "message" => "we cant create group for some reason",
                "error" => "bad request"
            ], 400);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return mixed
     */
    public function destroy($id)
    {
        Group::where("id", $id)->delete();
        return \response()
            ->json([
                null
            ], 204);
    }
}
