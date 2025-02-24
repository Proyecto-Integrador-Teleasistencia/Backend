<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historial de Llamadas del Paciente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .patient-info {
            margin-bottom: 20px;
        }
        .patient-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .section-title {
            color: #2c3e50;
            margin-top: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-data {
            text-align: center;
            margin: 40px 0;
            font-size: 16px;
            color: #888;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historial de Llamadas del Paciente</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="patient-card">
        <h2>Información del Paciente</h2>
        <p><strong>Nombre:</strong> {{ $patient->nombre }} {{ $patient->apellidos }}</p>
        <p><strong>ID:</strong> {{ $patient->id }}</p>
    </div>

    @if($calls->isEmpty())
        <div class="no-data">
            <p>No se encontraron llamadas para el paciente en el rango de fechas seleccionado.</p>
        </div>
    @else
        <h3 class="section-title">Historial de Llamadas</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha/Hora</th>
                    <th>Tipo de Llamada</th>
                    <th>Operador</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calls as $call)
                    <tr>
                        <td>{{ $call->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($call->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst($call->tipo_llamada) }}</td>
                        <td>{{ $call->operador ? $call->operador->nombre : 'No asignado' }}</td>
                        <td>{{ $call->categoria ? $call->categoria->nombre : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Generado por Teleasistencia - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
