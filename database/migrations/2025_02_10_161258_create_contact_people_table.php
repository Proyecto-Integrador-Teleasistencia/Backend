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
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('telefono', 20);
            $table->string('parentesco', 50);
            $table->text('direccion');
            $table->string('disponibilidad');
            $table->boolean('tiene_llaves')->default(false);
            $table->integer('nivel_prioridad')->default(1);
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index('nombre');
            $table->index('telefono');
            $table->index('paciente_id');
            $table->index('nivel_prioridad');
            $table->index('tiene_llaves');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
