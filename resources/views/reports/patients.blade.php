<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe Detallado de Llamada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .call-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .call-header {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 10px;
        }
        .section-title {
            color: #2c3e50;
            margin-top: 10px;
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
    <h1>Informe Detallado de Llamada</h1>
    <p>Fecha de Generación: {{ now()->format('d/m/Y H:i:s') }}</p>

    <div class="call-card">
        <div class="call-header">
            <h2>ID de Llamada: {{ $llamada['id'] }}</h2>
            <p><strong>Fecha y Hora:</strong> {{ \Carbon\Carbon::parse($llamada['fecha_hora'])->format('d/m/Y H:i') }}</p>
        </div>

        <h3 class="section-title">Detalles Generales</h3>
        <table>
            <tr><th>Tipo de Llamada</th><td>{{ ucfirst($llamada['tipo_llamada']) }}</td></tr>
            <tr><th>Duración</th><td>{{ gmdate('i:s', $llamada['duracion']) }} minutos</td></tr>
            <tr><th>Estado</th><td>{{ ucfirst($llamada['estado']) }}</td></tr>
        </table>

        <h3 class="section-title">Motivo y Descripción</h3>
        <table>
            <tr><th>Motivo</th><td>{{ $llamada['motivo'] }}</td></tr>
            <tr><th>Descripción</th><td>{{ $llamada['descripcion'] }}</td></tr>
        </table>

        <h3 class="section-title">Detalles de Programación</h3>
        <table>
            <tr><th>Planificada</th><td>{{ $llamada['planificada'] ? 'Sí' : 'No' }}</td></tr>
            <tr><th>Fecha Completada</th><td>{{ $llamada['fecha_completada'] ? \Carbon\Carbon::parse($llamada['fecha_completada'])->format('d/m/Y H:i') : 'No completada' }}</td></tr>
        </table>

        <h3 class="section-title">Identificación Relacionada</h3>
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
