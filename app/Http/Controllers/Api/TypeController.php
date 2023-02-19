<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TypeCreateRequest;
use App\Http\Requests\Api\TypeUpdateRequest;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Type::class, 'type');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['types' => Type::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TypeCreateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TypeCreateRequest $request)
    {
        $data = $request->validated();

        $type = Type::create($data);

        return response()->json(['type' => $type], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Type $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Type $type)
    {
        return response()->json(['type' => $type]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TypeUpdateRequest $request
     * @param  Type $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TypeUpdateRequest $request, Type $type)
    {
        $data = $request->validated();

        $type->fill($data)->save();

        return response()->json(['type' => $type]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Type $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Type $type)
    {
        $type->delete();

        return response()->json(['message' => 'type successfully deleted']);
    }
}
