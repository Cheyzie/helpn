<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BillCreateRequest;
use App\Http\Requests\Api\BillUpdateRequest;
use App\Models\Bill;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Bill::class);

        return response()->json(['bills' => Bill::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BillCreateRequest  $request
     * @return JsonResponse
     */
    public function store(BillCreateRequest $request): JsonResponse
    {
        if($request->user()->cannot('create', Bill::class)) {
            abort(403);
        }

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $bill = Bill::create($data);
        return response()->json(['bill' => $bill], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  Bill $bill
     * @return JsonResponse
     */
    public function show(Request $request, Bill $bill): JsonResponse
    {
        if($request->user()->cannot('view', $bill)) {
            abort(403);
        }

        $bill->makeVisible(['contacts']);

        return response()->json(['bill' => $bill]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BillUpdateRequest  $request
     * @param  Bill  $bill
     * @return JsonResponse
     */
    public function update(BillUpdateRequest $request, Bill $bill): JsonResponse
    {
        if($request->user()->cannot('update', $bill)) {
            abort(403);
        }

        $data = $request->validated();

        $bill->fill($data);
        $bill->save();

        $bill->makeVisible(['contacts']);
        return response()->json(['bill' => $bill]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Bill $bill
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Bill $bill):JsonResponse
    {
        $this->authorize('delete', $bill);

        $bill->delete();

        return response()->json(['message'=>'Bill successfully deleted']);
    }
}
