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
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefono', 20)->nullable();
            $table->enum('role', ['admin', 'operator'])->default('operator');
            $table->date('fecha_contratacion')->nullable();
            $table->date('fecha_baja')->nullable();
            $table->enum('turno', ['mañana', 'tarde', 'noche'])->nullable();
            $table->enum('estado', ['active', 'inactive', 'on_leave'])->default('active');
            $table->foreignId('zona_id')->nullable()->constrained('zonas')->onDelete('set null');
            
            // Índices para búsquedas frecuentes
            $table->index('estado');
            $table->index('turno');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['estado']);
            $table->dropIndex(['turno']);
            $table->dropColumn([
                'turno',
                'estado'
            ]);
        });
    }
};
