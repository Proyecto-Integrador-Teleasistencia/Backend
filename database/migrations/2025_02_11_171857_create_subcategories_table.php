<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcategorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index('nombre');
            $table->index('categoria_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategorias');
    }
};
