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
        .section {
            margin-bottom: 20px;
        }
        h2 {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border-radius: 5px;
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

    <div class="section">
        <h2>Detall de Cridada</h2>
        <table>
            <tr><th>ID</th><td>{{ $llamada->id }}</td></tr>
            <tr><th>Pacient</th><td>{{ $llamada->paciente->nombre ?? 'No especificat' }} {{ $llamada->paciente->apellidos ?? '' }}</td></tr>
            <tr><th>Data</th><td>{{ $llamada->completed_at ? \Carbon\Carbon::parse($llamada->completed_at)->format('d/m/Y H:i') : 'Data no registrada' }}</td></tr>
            <tr><th>Tipus</th><td>{{ ucfirst($llamada->tipo_llamada) ?? 'No especificat' }}</td></tr>
            <tr><th>Operador</th><td>{{ $llamada->operador->nombre ?? 'No especificat' }}</td></tr>
        </table>
    </div>

    <div class="footer">
        <p>Generat per Teleasistencia - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
