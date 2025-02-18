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
        Schema::create('zonas_usuario', function (Blueprint $table) {
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('zona_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->primary(['usuario_id', 'zona_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonas_usuario');
    }
};
