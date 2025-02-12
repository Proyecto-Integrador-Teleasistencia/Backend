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
        Schema::create('contact_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('phone', 20);
            $table->string('relationship', 50);
            $table->text('address');
            $table->string('availability');
            $table->boolean('has_keys')->default(false);
            $table->integer('priority_level')->default(1);
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index('name');
            $table->index('phone');
            $table->index('patient_id');
            $table->index('priority_level');
            $table->index('has_keys');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_people');
    }
};
