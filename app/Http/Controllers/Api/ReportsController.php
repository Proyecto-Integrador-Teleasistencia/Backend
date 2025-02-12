<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Call;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
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

        return response()->json($stats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:daily,weekly,monthly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'format' => 'required|string|in:pdf,csv,excel',
        ]);

        // Here you would implement the logic to generate and store reports
        // This is a placeholder that returns a success message
        return response()->json([
            'message' => 'Report generation started',
            'report_id' => uniqid('report_'),
        ], 202);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Here you would implement the logic to retrieve a specific report
        // This is a placeholder that returns a not found response
        return response()->json(['message' => 'Report not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Reports typically don't need to be updated
        return response()->json(['message' => 'Method not allowed'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Here you would implement the logic to delete a stored report
        // This is a placeholder that returns a success message
        return response()->json(null, 204);
    }
}
