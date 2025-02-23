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
            $table->integer('dia_semana')->nullable()->comment('1-7, donde 1 es lunes. Solo para avisos periódicos');
            $table->enum('tipo_aviso', [
                'medicacion',
                'especial',
                'seguimiento_emergencia',
                'seguimiento_dol',
                'seguimiento_alta',
                'ausencia_temporal',
                'retorno',
                'preventivo' 
            ]);
            $table->text('descripcion')->nullable();
            $table->boolean('completado')->default(false);
            $table->dateTime('fecha_completado')->nullable();

            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('paciente_id')->nullable()->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('zona_id')->nullable()->constrained('zonas')->onDelete('cascade')->comment('Si es un aviso preventivo que afecta a toda una zona');
            $table->foreignId('operador_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            $table->index('fecha_hora');
            $table->index('tipo');
            $table->index('tipo_aviso');
            $table->index('dia_semana');
            $table->index('completado');
            $table->index(['categoria_id', 'paciente_id']);
            $table->index('operador_id');
            $table->index('zona_id');
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
