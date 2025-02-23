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
            $table->string('tipo_llamada_detalle')->nullable()->comment('Subcategoría de la llamada (ej. emergencia social, notificación, etc.)');
            $table->integer('duracion')->comment('Duración en segundos');
            $table->enum('estado', ['completada', 'perdida', 'en_curso'])->default('en_curso');
            $table->string('motivo');
            $table->text('descripcion')->nullable();
            $table->boolean('planificada')->default(false);
            $table->dateTime('fecha_completada')->nullable();

            $table->foreignId('operador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('subcategoria_id')->constrained('subcategorias')->onDelete('cascade');
            $table->foreignId('aviso_id')->nullable()->constrained('avisos')->onDelete('set null')->comment('Solo si la llamada es planificada y está relacionada con un aviso');

            $table->timestamps();

            // Indexaciones
            $table->index('fecha_hora');
            $table->index('tipo_llamada');
            $table->index('estado');
            $table->index(['operador_id', 'paciente_id']);
            $table->index('categoria_id');
            $table->index('subcategoria_id');
            $table->index('aviso_id');
            $table->index('fecha_completada');
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
