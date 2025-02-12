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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->dateTime('datetime');
            $table->text('description')->nullable();
            $table->enum('type', ['outgoing', 'incoming']);
            $table->boolean('scheduled')->default(false);
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('alert_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            // Indexar campos de búsqueda frecuente
            $table->index('datetime');
            $table->index('type');
            $table->index('scheduled');
            $table->index(['operator_id', 'patient_id']);
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
