<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('telefono', 20);
            $table->string('relacion', 50)->default('Familiar');
            $table->timestamps();
            
            $table->index('nombre');
            $table->index('telefono');
            $table->index('paciente_id');
            $table->index('relacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
