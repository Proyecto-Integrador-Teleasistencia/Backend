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
        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['puntual', 'periodico']);
            $table->dateTime('fecha_hora');
            $table->text('descripcion')->nullable();
            $table->boolean('completado')->default(false);
            $table->dateTime('fecha_completado')->nullable();
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('operador_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexar campos de búsqueda frecuente
            $table->index('fecha_hora');
            $table->index('tipo');
            $table->index('completado');
            $table->index(['categoria_id', 'paciente_id']);
            $table->index('operador_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avisos');
    }
};
