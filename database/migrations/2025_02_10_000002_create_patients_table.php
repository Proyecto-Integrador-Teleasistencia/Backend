<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->date('fecha_nacimiento');
            $table->text('direccion');
            $table->string('ciudad')->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->string('dni', 20)->unique();
            $table->string('tarjeta_sanitaria', 50)->unique();
            $table->string('telefono', 20);
            $table->string('email', 100)->nullable()->unique();
            $table->foreignId('zona_id')->constrained()->onDelete('restrict');
            
            // Campos de situación
            $table->text('situacion_personal')->nullable();
            $table->text('estado_salud')->nullable();
            $table->text('condicion_vivienda')->nullable();
            $table->text('nivel_autonomia')->nullable();
            $table->text('situacion_economica')->nullable();

            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index('nombre');
            $table->index('dni');
            $table->index('tarjeta_sanitaria');
            $table->index('telefono');
            $table->index('zona_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
