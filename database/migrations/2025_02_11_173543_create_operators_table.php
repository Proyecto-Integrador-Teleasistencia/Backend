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
            $table->string('phone', 20)->nullable();
            $table->enum('role', ['admin', 'operator'])->default('operator');
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->enum('shift', ['morning', 'afternoon', 'night'])->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('set null');
            
            // Índices para búsquedas frecuentes
            $table->index('role');
            $table->index('status');
            $table->index('zone_id');
            $table->index('shift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);
            $table->dropIndex(['zone_id']);
            $table->dropIndex(['shift']);
            $table->dropForeign(['zone_id']);
            $table->dropColumn([
                'phone',
                'role',
                'hire_date',
                'termination_date',
                'shift',
                'status',
                'zone_id'
            ]);
        });
    }
};
