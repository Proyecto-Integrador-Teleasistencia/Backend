<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Aviso;
use App\Models\Llamada;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Resources\CallResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\AvisoResource;
use OpenApi\Annotations as OA;

class ReportsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/reports/check-emergencies",
     *     summary="Verificar emergencias en una zona",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="zone_id", in="query", description="ID de la zona", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="start_date", in="query", description="Fecha de inicio (Y-m-d)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="end_date", in="query", description="Fecha de fin (Y-m-d)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta de verificación de emergencias",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    public function checkEmergencies(Request $request)
    {
        $zoneId = $request->input('zona_id');
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : null;
        
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : null;

        if (!$zoneId) {
            return $this->sendError('Es necesario especificar una zona');
        }

        $zone = \App\Models\Zona::findOrFail($zoneId);
        $avisos = Aviso::where('tipo', 'puntual')->with('paciente', 'operador', 'zona')->get();
        $emergencias = $avisos->filter(function ($aviso) use ($zoneId, $startDate, $endDate) {
            $filter = $aviso->paciente->zona_id == $zoneId;

            if ($startDate) {
                $filter = $filter && $aviso->fecha_hora >= $startDate;
            }

            if ($endDate) {
                $filter = $filter && $aviso->fecha_hora <= $endDate;
            }

            return $filter;
        });

        return $this->sendResponse([
            'zone' => $zone->nombre,
            'has_emergencias' => $emergencias->count(),
            'total_emergencias' => Aviso::where('tipo', 'puntual')
                ->whereHas('paciente', function ($query) use ($zoneId, $startDate, $endDate) {
                    $query->where('zona_id', $zoneId)->whereBetween('fecha_hora', [$startDate, $endDate]);
                })
                ->count(),
            'emergencias' => $emergencias
        ], 'Comprobación de emergencias completada');
    }

    /**
     * @OA\Get(
     *     path="/api/reports/emergency-report",
     *     summary="Generar informe de emergencias para una zona",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="zone_id", in="query", description="ID de la zona", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="start_date", in="query", description="Fecha de inicio (Y-m-d)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="end_date", in="query", description="Fecha de fin (Y-m-d)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(
     *         response=200,
     *         description="PDF del informe de emergencias"
     *     )
     * )
     */
    public function emergencyReport(Request $request)
    {
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth()->startOfDay();
        
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $zoneId = $request->input('zona_id');

        if (!$zoneId) {
            return $this->sendError('Es necesario especificar una zona');
        }

        $query = Aviso::with(['paciente', 'operador', 'categoria'])
            ->where('tipo', 'puntual')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('paciente', function ($query) use ($zoneId) {
                $query->where('zona_id', $zoneId);
            })
            ->orderBy('created_at', 'desc');

        $emergencies = $query->get();
        $zone = \App\Models\Zona::findOrFail($zoneId);

        if ($emergencies->isEmpty()) {
            return $this->sendError('No hay emergencias en esta zona para el período especificado');
        }

        $pdf = \PDF::loadView('reports.emergencies', compact('emergencies', 'zone', 'startDate', 'endDate'));
        $pdf->setPaper('a4');

        $filename = 'informe_emergencies_zona_' . Str::slug($zone->nombre) . '_' . now()->format('Y-m-d_His') . '.pdf';
        return $pdf->download($filename);

    }

    /**
     * @OA\Get(
     *     path="/api/reports/pacientes",
     *     summary="Obtener lista de pacientes ordenados por apellido",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pacientes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/PatientResource"))
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

            return $pdfs->download($filename);
        } catch (\Exception $e) {
            return $this->sendError('Error al generar la llista d\'informes de pacients', $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reports/llamadas-programadas",
     *     summary="Obtener llamadas programadas",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="format", in="query", description="Formato de exportación (pdf/csv)", required=false, @OA\Schema(type="string", enum={"pdf", "csv"})),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de llamadas programadas",
     *     )
     * )
     */
    public function scheduledCalls(Request $request)
    {
        $format = $request->input('format', 'pdf');
        $id = $request->input('id');
        $llamada = Llamada::with(['paciente', 'operador'])->findOrFail($id);

        try {
            if ($format === 'csv') {
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="llamadas_programadas_' . $llamada->id . '.csv"',
                ];

                $callback = function() use ($llamada) {
                    $file = fopen('php://output', 'w'); 
                    fputcsv($file, ['ID', 'Paciente', 'Fecha Programada', 'Tipo', 'Estado', 'Operador']);
                    foreach ($llamada as $call) {
                        fputcsv($file, [
                            $llamada->id,
                            $llamada->paciente->nombre . ' ' . $llamada->paciente->apellidos,
                            $llamada->fecha_hora,
                            $llamada->tipo_llamada,
                            $llamada->estado,
                            $llamada->operador ? $llamada->operador->nombre : 'No asignado'
                        ]);
                    }
                    fclose($file);
                };

                return response()->streamDownload($callback, 'llamadas_programadas_' . $llamada->id . '.csv', $headers);
            }

            $pdf = \PDF::loadView('reports.scheduled_calls', compact('llamada'));
            $pdf->setPaper('a4');
            $filename = 'llamadas_programadas_' . (is_string($llamada->fecha_hora) ? $llamada->fecha_hora : $llamada->fecha_hora->format('d-m-Y')) . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return $this->sendError('Error al generar el listado de llamadas programadas', $e->getMessage());
        }
    }

   /**
     * @OA\Get(
     *     path="/api/reports/llamadas-realizadas",
     *     summary="Obtener llamadas completadas del día",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="date", in="query", description="Fecha a consultar (Y-m-d)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de llamadas completadas"
     *     )
     * )
     */
    public function doneCalls(Request $request)
    {
        $format = $request->input('format', 'pdf'); // 'json', 'pdf', o 'csv'
        $id = $request->input('id');
        $llamada = Llamada::with(['paciente', 'operador'])->findOrFail($id);


        switch($format) {
            case 'pdf':
                $pdf = \PDF::loadView('reports.done_calls', compact('llamada'));
                $pdf->setPaper('a4');
                $filename = 'llamadas_realizadas_' . (is_string($llamada->fecha_hora) ? $llamada->fecha_hora : $llamada->fecha_hora->format('d-m-Y')) . '.pdf';
                return $pdf->download($filename);
            case 'csv':
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename=llamadas_realizadas_' . $date . '.csv',
                ];
                
                $callback = function() use ($llamada) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['ID', 'Paciente', 'Fecha', 'Tipo', 'Estado', 'Operador']);
                    
                    foreach ($llamada as $call) {
                        fputcsv($file, [
                            $call->id,
                            $call->paciente->nombre . ' ' . $call->paciente->apellidos,
                            $call->fecha_hora,
                            $call->tipo_llamada,
                            $call->estado,
                            $call->operador->name
                        ]);
                    }
                    
                    fclose($file);
                };
                
                return $this->sendResponse(
                    $calls,
                    'Llistat de cridades realitzades recuperat amb èxit'
                );
            
            default:
                return $this->sendResponse(
                    CallResource::collection($calls),
                    'Llistat de cridades realitzades recuperat amb èxit'
                );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reports/historial-paciente/{id}",
     *     summary="Obtener historial de llamadas de un paciente",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", description="ID del paciente", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="start_date", in="query", description="Fecha de inicio (Y-m-d)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="end_date", in="query", description="Fecha de fin (Y-m-d)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(
     *         response=200,
     *         description="Historial de llamadas del paciente"
     *     )
     * )
     */
    public function patientHistory(Request $request, $patientId)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3));
        $endDate = $request->input('end_date', Carbon::now());
        $type = $request->input('type');

        $patient = Paciente::findOrFail($patientId);
        
        $query = $patient->llamadas()
            ->with(['operador', 'categoria'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('tipo_llamada', $type);
        }

        $calls = $query->get();

        $pdf = \PDF::loadView('reports.patient_history', compact('patient', 'calls', 'startDate', 'endDate'));
        $pdf->setPaper('a4');
        $startDateObj = Carbon::parse($startDate);
        $endDateObj = Carbon::parse($endDate);
        $filename = 'històric_de_cridades_' . $patient->nombre . '_' . $startDateObj->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);

    }
}
