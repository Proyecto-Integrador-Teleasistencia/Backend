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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->date('birth_date');
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('dni', 20)->unique();
            $table->string('health_card', 50)->unique();
            $table->string('phone', 20);
            $table->string('email', 100)->nullable()->unique();
            $table->foreignId('zone_id')->constrained()->onDelete('restrict');
            
            // Campos de situación
            $table->text('personal_situation')->nullable();
            $table->text('health_condition')->nullable();
            $table->text('home_condition')->nullable();
            $table->text('autonomy_level')->nullable();
            $table->text('economic_situation')->nullable();
            
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index('name');
            $table->index('dni');
            $table->index('health_card');
            $table->index('phone');
            $table->index('zone_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
