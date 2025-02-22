<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe d'Emergències per Zona</title>
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
        .zone-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .date-range {
            margin-bottom: 20px;
        }
        .stats {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
        }
        .stat-box {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Informe d'Emergències per Zona</h1>
    </div>

    <div class="zone-info">
        <h2>Zona: {{ $zone->nombre }}</h2>
        <p><strong>Codi:</strong> {{ $zone->codigo }}</p>
    </div>

    <div class="date-range">
        <strong>Període:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
    </div>

    <div class="stats">
        <div class="stat-box">
            <strong>Total d'Emergències:</strong>
            <div>{{ $emergencies->count() }}</div>
        </div>
        <div class="stat-box">
            <strong>Pacients Afectats:</strong>
            <div>{{ $emergencies->unique('paciente_id')->count() }}</div>
        </div>
        <div class="stat-box">
            <strong>Operadors Involucrats:</strong>
            <div>{{ $emergencies->unique('operador_id')->count() }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data i Hora</th>
                <th>Pacient</th>
                <th>Operador</th>
                <th>Categoria</th>
                <th>Descripció</th>
                <th>Estat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emergencies as $emergency)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($emergency->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $emergency->paciente->nombre ?? 'N/A' }}</td>
                    <td>{{ $emergency->operador->nombre ?? 'N/A' }}</td>
                    <td>{{ $emergency->categoria->nombre ?? 'N/A' }}</td>
                    <td>{{ $emergency->descripcion ?? 'N/A' }}</td>
                    <td>{{ $emergency->completado ? 'Completada' : 'Pendent' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Informe generat el {{ now()->format('d/m/Y H:i') }}</p>
        <p>Total d'emergències: {{ $emergencies->count() }}</p>
    </div>
</body>
</html>
