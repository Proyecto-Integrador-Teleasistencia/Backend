<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Paciente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .patient-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .patient-header {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 10px;
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
    <h1>Informe Detallado de Paciente</h1>
    <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>

    <div class="patient-card">
        <div class="patient-header">
            <h2>{{ $patient->nombre }}</h2>
            <p><strong>DNI:</strong> {{ $patient->dni }}</p>
            <p><strong>Tarjeta Sanitaria:</strong> {{ $patient->tarjeta_sanitaria }}</p>
        </div>

        <h3 class="section-title">Información Personal</h3>
        <table>
            <tr><th>Fecha de Nacimiento</th><td>{{ $patient->fecha_nacimiento->format('d/m/Y') }} ({{ $patient->edad }} años)</td></tr>
            <tr><th>Teléfono</th><td>{{ $patient->telefono }}</td></tr>
            <tr><th>Email</th><td>{{ $patient->email ?? 'No disponible' }}</td></tr>
        </table>

        <h3 class="section-title">Dirección</h3>
        <table>
            <tr><th>Dirección</th><td>{{ $patient->direccion }}</td></tr>
            <tr><th>Ciudad</th><td>{{ $patient->ciudad ?? 'Ciudad no especificada' }}</td></tr>
            <tr><th>Código Postal</th><td>{{ $patient->codigo_postal ?? 'CP no especificado' }}</td></tr>
            <tr><th>Zona</th><td>{{ $patient->zona->nombre }}</td></tr>
        </table>

        <h3 class="section-title">Estado</h3>
        <table>
            <tr><th>Situación Personal</th><td>{{ $patient->situacion_personal ?? 'No especificada' }}</td></tr>
            <tr><th>Estado de Salud</th><td>{{ $patient->estado_salud ?? 'No especificado' }}</td></tr>
            <tr><th>Nivel de Autonomía</th><td>{{ $patient->nivel_autonomia ?? 'No especificado' }}</td></tr>
            <tr><th>Condición de Vivienda</th><td>{{ $patient->condicion_vivienda ?? 'No especificada' }}</td></tr>
            <tr><th>Situación Económica</th><td>{{ $patient->situacion_economica ?? 'No especificada' }}</td></tr>
        </table>

        @if($patient->contactos->count() > 0)
            <h3 class="section-title">Contactos de Emergencia</h3>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Relación</th>
                    <th>Prioridad</th>
                    <th>Llaves</th>
                </tr>
                @foreach($patient->contactos as $contacto)
                    <tr>
                        <td>{{ $contacto->nombre }} {{ $contacto->apellido }}</td>
                        <td>{{ $contacto->telefono }}</td>
                        <td>{{ $contacto->relacion }}</td>
                        <td>{{ $contacto->nivel_prioridad }}</td>
                        <td>{{ $contacto->tiene_llaves ? 'Sí' : 'No' }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>No hay contactos de emergencia registrados.</p>
        @endif
    </div>

    <div class="footer">
        <p>Generado por Teleasistencia - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
