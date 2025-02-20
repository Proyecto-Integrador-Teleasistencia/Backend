<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Paciente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
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
        .contact-list {
            margin-left: 20px;
        }
        .section-title {
            color: #2c3e50;
            margin-top: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-row {
            margin: 10px 0;
        }
        .info-label {
            font-weight: bold;
            color: #34495e;
        }
    </style>
</head>
<body>
    <h1>Informe de Paciente</h1>
    <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>

    <div class="patient-card">
        <div class="patient-header">
            <h2>{{ $patient->nombre }}</h2>
            <p class="info-row"><span class="info-label">DNI:</span> {{ $patient->dni }}</p>
            <p class="info-row"><span class="info-label">Tarjeta Sanitaria:</span> {{ $patient->tarjeta_sanitaria }}</p>
        </div>

        <h3 class="section-title">Información Personal</h3>
        <p class="info-row"><span class="info-label">Fecha de Nacimiento:</span> {{ $patient->fecha_nacimiento->format('d/m/Y') }} ({{ $patient->edad }} años)</p>
        <p class="info-row"><span class="info-label">Teléfono:</span> {{ $patient->telefono }}</p>
        <p class="info-row"><span class="info-label">Email:</span> {{ $patient->email ?? 'No disponible' }}</p>

        <h3 class="section-title">Dirección</h3>
        <p class="info-row">{{ $patient->direccion }}</p>
        <p class="info-row">{{ $patient->ciudad ?? 'Ciudad no especificada' }} - {{ $patient->codigo_postal ?? 'CP no especificado' }}</p>
        <p class="info-row"><span class="info-label">Zona:</span> {{ $patient->zona->nombre }}</p>

        <h3 class="section-title">Estado</h3>
        <p class="info-row"><span class="info-label">Situación Personal:</span> {{ $patient->situacion_personal ?? 'No especificada' }}</p>
        <p class="info-row"><span class="info-label">Estado de Salud:</span> {{ $patient->estado_salud ?? 'No especificado' }}</p>
        <p class="info-row"><span class="info-label">Nivel de Autonomía:</span> {{ $patient->nivel_autonomia ?? 'No especificado' }}</p>
        <p class="info-row"><span class="info-label">Condición de Vivienda:</span> {{ $patient->condicion_vivienda ?? 'No especificada' }}</p>
        <p class="info-row"><span class="info-label">Situación Económica:</span> {{ $patient->situacion_economica ?? 'No especificada' }}</p>

        @if($patient->contactos->count() > 0)
            <h3 class="section-title">Contactos de Emergencia</h3>
            <div class="contact-list">
                @foreach($patient->contactos as $contacto)
                    <div style="margin-bottom: 15px;">
                        <p class="info-row">
                            <span class="info-label">Nombre:</span> {{ $contacto->nombre }} {{ $contacto->apellido }}<br>
                            <span class="info-label">Teléfono:</span> {{ $contacto->telefono }}<br>
                            <span class="info-label">Relación:</span> {{ $contacto->relacion }}<br>
                            <span class="info-label">Prioridad:</span> {{ $contacto->nivel_prioridad }}<br>
                            @if($contacto->tiene_llaves)
                                <em style="color: #27ae60;">Tiene llaves de la vivienda</em>
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p>No hay contactos de emergencia registrados.</p>
        @endif
    </div>
</body>
</html>
