<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe d'Emergències per Zona</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .zone-info {
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
        .stats {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .stat-box {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            flex: 1;
            margin: 0 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
        <h1>Informe d'Emergències per Zona</h1>
        <p>Informe generat el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="zone-info">
        <h2>Zona: {{ $zone->nombre }}</h2>
        <p><strong>Codi:</strong> {{ $zone->codigo }}</p>
        <p><strong>Període:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <h3 class="section-title">Estadístiques</h3>
    <div class="stats">
        <div class="stat-box">
            <strong>Total d'Emergències</strong>
            <div>{{ $emergencies->count() }}</div>
        </div>
        <div class="stat-box">
            <strong>Pacients Afectats</strong>
            <div>{{ $emergencies->unique('paciente_id')->count() }}</div>
        </div>
        <div class="stat-box">
            <strong>Operadors Involucrats</strong>
            <div>{{ $emergencies->unique('operador_id')->count() }}</div>
        </div>
    </div>

    <h3 class="section-title">Detall d'Emergències</h3>
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
        <p>Generat per Teleasistencia - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
