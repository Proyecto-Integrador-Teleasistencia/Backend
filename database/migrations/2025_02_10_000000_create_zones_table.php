<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zonas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('codigo', 50)->unique();
            $table->boolean('activa')->default(true);
            $table->timestamps();
            
            $table->index('nombre');
            $table->index('codigo');
            $table->index('activa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zonas');
    }
};
