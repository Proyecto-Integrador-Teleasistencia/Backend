<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Alert;
use App\Models\Call;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CallResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\AlertResource;

class ReportsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/reports/emergencies",
     *     summary="Get emergency actions report",
     *     tags={"Reports"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Emergency actions report"
     *     )
     * )
     */
    public function emergencies(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());

        $emergencies = Alert::with(['patient', 'operator'])
            ->where('type', 'alarm')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse(
            AlertResource::collection($emergencies),
            'Informe d\'emergències recuperat amb èxit'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/reports/patients",
     *     summary="Get patients list ordered by surname",
     *     tags={"Reports"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of patients"
     *     )
     * )
     */
    public function patients()
    {
        $patients = Patient::with(['zone', 'contacts'])
            ->orderBy('surname')
            ->paginate(50);

        return $this->sendResponse(
            PatientResource::collection($patients),
            'Llistat de pacients recuperat amb èxit'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/reports/scheduled-calls",
     *     summary="Get scheduled calls for a day",
     *     tags={"Reports"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Date to check (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of scheduled calls"
     *     )
     * )
     */
    public function scheduledCalls(Request $request)
    {
        $date = $request->input('date', Carbon::today());
        
        $calls = Call::with(['patient', 'operator'])
            ->where('status', 'scheduled')
            ->whereDate('scheduled_for', $date)
            ->orderBy('scheduled_for')
            ->get();

        return $this->sendResponse(
            CallResource::collection($calls),
            'Llistat de cridades previstes recuperat amb èxit'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/reports/done-calls",
     *     summary="Get completed calls for a day",
     *     tags={"Reports"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Date to check (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of completed calls"
     *     )
     * )
     */
    public function doneCalls(Request $request)
    {
        $date = $request->input('date', Carbon::today());
        
        $calls = Call::with(['patient', 'operator'])
            ->where('status', 'completed')
            ->whereDate('completed_at', $date)
            ->orderBy('completed_at', 'desc')
            ->get();

        return $this->sendResponse(
            CallResource::collection($calls),
            'Llistat de cridades realitzades recuperat amb èxit'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/reports/patient-history/{id}",
     *     summary="Get patient's call history",
     *     tags={"Reports"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient's call history"
     *     )
     * )
     */
    public function patientHistory(Request $request, $patientId)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3));
        $endDate = $request->input('end_date', Carbon::now());

        $patient = Patient::findOrFail($patientId);
        
        $calls = $patient->calls()
            ->with(['operator', 'category'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse([
            'patient' => new PatientResource($patient),
            'calls' => CallResource::collection($calls)
        ], 'Històric de cridades del pacient recuperat amb èxit');
    }
}
