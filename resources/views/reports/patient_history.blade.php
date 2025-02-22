<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historial de Cridades del Pacient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .patient-info {
            margin-bottom: 20px;
        }
        .patient-info h2 {
            margin-bottom: 10px;
        }
        .patient-info p {
            margin: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
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
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historial de Cridades del Pacient</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="patient-info">
        <h2>Información del Pacient</h2>
        <p><strong>Nombre:</strong> {{ $patient->nombre }} {{ $patient->apellidos }}</p>
        <p><strong>ID:</strong> {{ $patient->id }}</p>
        <!-- Puedes agregar más datos del paciente aquí -->
    </div>

    @if($calls->isEmpty())
        <div class="no-data">
            <p>No se encontraron cridades para el paciente en el rango de fechas seleccionado.</p>
        </div>
    @else
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
                        <td>{{ $call->tipo_llamada }}</td>
                        <td>{{ $call->operador ? $call->operador->nombre : 'No asignado' }}</td>
                        <td>{{ $call->categoria ? $call->categoria->nombre : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Generat per Teleasistencia - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
