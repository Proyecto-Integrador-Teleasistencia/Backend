<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Aviso;
use App\Models\Llamada;
use App\Models\Paciente;
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

        $emergencies = Aviso::with(['paciente', 'operador'])
            ->where('tipo', 'periodico')
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
    public function patients($id)
    {
        try {
            $patient = Paciente::with(['zona', 'contactos'])
                ->findOrFail($id);

            $pdf = \PDF::loadView('reports.patient', compact('patient'));
            $pdf->setPaper('a4');

            $filename = 'informe_pacientes_' . now()->format('Y-m-d_His') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return $this->sendError('Error al generar el informe de pacientes', $e->getMessage());
        }
    }

    public function getAllInformes()
    {
        try {
            $patients = Paciente::with(['zona', 'contactos'])->get();
            $pdfs = [];

            foreach ($patients as $patient) {
                $pdf = \PDF::loadView('reports.patient', compact('patient'));
                $pdf->setPaper('a4');

                $filename = 'informe_pacientes_' . $patient->id . '_' . now()->format('Y-m-d_His') . '.pdf';
                $pdfs[] = $pdf->stream($filename);
            }

            return $this->sendResponse($pdfs, 'Llista d\'informes de pacients generats amb èxit');
        } catch (\Exception $e) {
            return $this->sendError('Error al generar la llista d\'informes de pacients', $e->getMessage());
        }
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
        $type = $request->input('type');
        $zoneId = $request->input('zone_id');
        
        $calls = Llamada::with(['paciente', 'operador'])
            ->where('status', 'planificada')
            ->whereDate('scheduled_for', $date);

        if ($type) {
            $calls->where('tipo_llamada', $type);
        }

        if ($zoneId) {
            $calls->whereHas('paciente', function ($query) use ($zoneId) {
                $query->where('zona_id', $zoneId);
            });
        }

        $calls = $calls->orderBy('scheduled_for')
            ->get();

        try {
            $pdfs = [];
            foreach ($calls as $call) {
                $pdf = \PDF::loadView('reports.scheduled_call', compact('call'));
                $pdf->setPaper('a4');

                $filename = 'informe_crida_' . $call->id . '_' . now()->format('Y-m-d_His') . '.pdf';
                $pdfs[] = $pdf->stream($filename);
            }

            return $this->sendResponse($pdfs, 'Llistat de cridades previstes recuperat amb èxit');
        } catch (\Exception $e) {
            return $this->sendError('Error al generar el llistat de cridades previstes', $e->getMessage());
        }
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
        
        $calls = Llamada::with(['paciente', 'operador'])
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

        $patient = Paciente::findOrFail($patientId);
        
        $calls = $patient->llamadas()
            ->with(['operador', 'categoria'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse([
            'patient' => new PatientResource($patient),
            'calls' => CallResource::collection($calls)
        ], 'Històric de cridades del pacient recuperat amb èxit');
    }
}
