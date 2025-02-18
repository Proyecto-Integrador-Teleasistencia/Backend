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
        Schema::create('llamadas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora');
            $table->enum('tipo_llamada', ['entrante', 'saliente']);
            $table->integer('duracion')->comment('Duración en segundos');
            $table->enum('estado', ['completada', 'perdida', 'en_curso'])->default('en_curso');
            $table->string('motivo');
            $table->text('descripcion')->nullable();
            $table->foreignId('operador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('subcategoria_id')->constrained('subcategorias')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('fecha_hora');
            $table->index('tipo_llamada');
            $table->index('estado');
            $table->index(['operador_id', 'paciente_id']);
            $table->index('categoria_id');
            $table->index('subcategoria_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llamadas');
    }
};
