<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Cridades Realitzades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
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
        <h1>Informe de Cridades Realitzades</h1>
        <p>Data de Generació: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <h3 class="section-title">Detall de Cridades</h3>
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
                    <td>{{ ucfirst($call->tipo_llamada) }}</td>
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
