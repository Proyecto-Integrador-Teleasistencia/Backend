<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Cridades Realitzades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
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
        <h1>Informe de Cridades Realitzades</h1>
        <p>Data: {{ now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pacient</th>
                <th>Data</th>
                <th>Tipus</th>
                <th>Operador</th>
            </tr>
        </thead>
        <tbody>
            @foreach($calls as $call)
                <tr>
                    <td>{{ $call->id }}</td>
                    <td>{{ $call->paciente->nombre }} {{ $call->paciente->apellidos }}</td>
                    <td>{{ \Carbon\Carbon::parse($call->completed_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $call->tipo_llamada }}</td>
                    <td>{{ $call->operador->nombre }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generat per Teleasistencia - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
