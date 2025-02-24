<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Llamada Programada</title>
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
        <h1>Informe Detallado de Llamada</h1>
        <p>Fecha de Generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="section">
        <h2>Detalles Generales</h2>
        <table>
            <tr><th>ID de Llamada</th><td>{{ $llamada['id'] }}</td></tr>
            <tr><th>Fecha y Hora</th><td>{{ \Carbon\Carbon::parse($llamada['fecha_hora'])->format('d/m/Y H:i') }}</td></tr>
            <tr><th>Tipo de Llamada</th><td>{{ ucfirst($llamada['tipo_llamada']) }}</td></tr>
            <tr><th>Duración</th><td>{{ gmdate('i:s', $llamada['duracion']) }} minutos</td></tr>
            <tr><th>Estado</th><td>{{ ucfirst($llamada['estado']) }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h2>Motivo y Descripción</h2>
        <table>
            <tr><th>Motivo</th><td>{{ $llamada['motivo'] }}</td></tr>
            <tr><th>Descripción</th><td>{{ $llamada['descripcion'] }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h2>Detalles de Programación</h2>
        <table>
            <tr><th>Planificada</th><td>{{ $llamada['planificada'] ? 'Sí' : 'No' }}</td></tr>
            <tr><th>Fecha Completada</th><td>{{ $llamada['fecha_completada'] ? \Carbon\Carbon::parse($llamada['fecha_completada'])->format('d/m/Y H:i') : 'No completada' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h2>Identificación Relacionada</h2>
        <table>
            <tr><th>ID del Operador</th><td>{{ $llamada['operador_id'] }}</td></tr>
            <tr><th>ID del Paciente</th><td>{{ $llamada['paciente_id'] }}</td></tr>
            <tr><th>ID de Categoría</th><td>{{ $llamada['categoria_id'] }}</td></tr>
            <tr><th>ID de Subcategoría</th><td>{{ $llamada['subcategoria_id'] }}</td></tr>
            <tr><th>ID de Aviso</th><td>{{ $llamada['aviso_id'] ?? 'No asignado' }}</td></tr>
        </table>
    </div>

    <div class="footer">
        <p>Generado por Teleasistencia - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
