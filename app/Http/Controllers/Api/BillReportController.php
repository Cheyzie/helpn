<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportCreateRequest;
use App\Models\Bill;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class BillReportController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Report::class, 'report');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Bill $bill
     * @return JsonResponse
     */
    public function index(Bill $bill)
    {
        $reports = $bill->reports;

        return response()->json(['reports' => $reports]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Bill $bill
     * @param ReportCreateRequest $request
     * @return JsonResponse
     */
    public function store(Bill $bill, ReportCreateRequest $request)
    {
        $data = $request->validated();
        $data['bill_id'] = $bill->id;
        $data['user_id'] = $request->user()->id;

        $report = Report::create($data);

        return response()->json(['report' => $report], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Report $report
     * @return JsonResponse
     */
    public function show(Report $report)
    {
        return response()->json(['report'=>$report]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Report $report
     * @return JsonResponse
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return response()->json(['message' => 'report successfully deleted']);
    }
}
