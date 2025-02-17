<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Alert;
use App\Models\Call;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startDate = request('start_date', Carbon::now()->startOfMonth());
        $endDate = request('end_date', Carbon::now());

        $stats = [
            'total_patients' => Patient::count(),
            'total_calls' => Call::whereBetween('call_time', [$startDate, $endDate])->count(),
            'alerts_by_priority' => Alert::whereBetween('created_at', [$startDate, $endDate])
                ->select('priority', DB::raw('count(*) as count'))
                ->groupBy('priority')
                ->get(),
            'calls_by_type' => Call::whereBetween('call_time', [$startDate, $endDate])
                ->select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get(),
            'patients_by_zone' => Patient::select('zones.name', DB::raw('count(*) as count'))
                ->join('zones', 'patients.zone_id', '=', 'zones.id')
                ->groupBy('zones.name')
                ->get(),
        ];

        return $this->sendResponse($stats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate();

        // Here you would implement the logic to generate and store reports
        // This is a placeholder that returns a success message
        return $this->sendResponse([
            'message' => 'Report generation started',
            'report_id' => uniqid('report_'),
        ], 'Reporte generado', 202);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        return $this->sendResponse(new ReportResource($report), 'Reporte recuperado', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        // Reports typically don't need to be updated
        return $this->sendResponse(['message' => 'Method not allowed'], 'Metodo no permitido', 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return $this->sendResponse(['message' => 'Reporte eliminado'], 'Reporte eliminado', 200);
    }
}
