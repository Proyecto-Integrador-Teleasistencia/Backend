<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Pacientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .page-break {
            page-break-after: always;
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
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Informe de Pacientes</h1>
    <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>

    @foreach($patients as $patient)
        <div class="patient-card">
            <div class="patient-header">
                <h2>{{ $patient->nombre }}</h2>
                <p><strong>DNI:</strong> {{ $patient->dni }}</p>
            </div>

            <h3 class="section-title">Información Personal</h3>
            <p><strong>Fecha de Nacimiento:</strong> {{ $patient->fecha_nacimiento->format('d/m/Y') }} ({{ $patient->edad }} años)</p>
            <p><strong>Teléfono:</strong> {{ $patient->telefono }}</p>
            <p><strong>Email:</strong> {{ $patient->email ?? 'No disponible' }}</p>

            <h3 class="section-title">Dirección</h3>
            <p>{{ $patient->direccion }}</p>
            <p>{{ $patient->ciudad ?? 'Ciudad no especificada' }} - {{ $patient->codigo_postal ?? 'CP no especificado' }}</p>
            <p><strong>Zona:</strong> {{ $patient->zona->nombre }}</p>

            <h3 class="section-title">Estado</h3>
            <p><strong>Situación Personal:</strong> {{ $patient->situacion_personal ?? 'No especificada' }}</p>
            <p><strong>Estado de Salud:</strong> {{ $patient->estado_salud ?? 'No especificado' }}</p>
            <p><strong>Nivel de Autonomía:</strong> {{ $patient->nivel_autonomia ?? 'No especificado' }}</p>
            <p><strong>Condición de Vivienda:</strong> {{ $patient->condicion_vivienda ?? 'No especificada' }}</p>
            <p><strong>Situación Económica:</strong> {{ $patient->situacion_economica ?? 'No especificada' }}</p>

            @if($patient->contactos->count() > 0)
                <h3 class="section-title">Contactos de Emergencia</h3>
                <div class="contact-list">
                    @foreach($patient->contactos as $contacto)
                        <p>
                            <strong>{{ $contacto->nombre }} {{ $contacto->apellido }}</strong><br>
                            Teléfono: {{ $contacto->telefono }}<br>
                            Relación: {{ $contacto->relacion }}<br>
                            Prioridad: {{ $contacto->nivel_prioridad }}<br>
                            @if($contacto->tiene_llaves)
                                <em>Tiene llaves de la vivienda</em>
                            @endif
                        </p>
                    @endforeach
                </div>
            @endif
        </div>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
