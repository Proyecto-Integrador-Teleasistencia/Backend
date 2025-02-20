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
            $table->foreignId('zona_id')->nullable()->constrained('zonas')->onDelete('set null');
            
            // Índices para búsquedas frecuentes
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn([
                'telefono',
                'role',
                'fecha_contratacion',
                'fecha_baja',
                'zona_id'
            ]);
        });
    }
};
