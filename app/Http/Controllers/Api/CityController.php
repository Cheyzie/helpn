<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CityCreateRequest;
use App\Http\Requests\Api\CityUpdateRequest;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(City::class, 'city');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['cities' => City::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CityCreateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CityCreateRequest $request)
    {
        $data = $request->validated();

        $city = City::create($data);

        return response()->json(['city' => $city], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  City $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(City $city)
    {
        return response()->json(['city' => $city]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CityUpdateRequest $request
     * @param  City $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CityUpdateRequest $request, City $city)
    {
        $data = $request->validated();

        $city->fill($data)->save();

        return response()->json(['city' => $city]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  City  $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(City $city)
    {
        $city->delete();

        return response()->json(['message' => 'bill successfully deleted']);
    }
}
