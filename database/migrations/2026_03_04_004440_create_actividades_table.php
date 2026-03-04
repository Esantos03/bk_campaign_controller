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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campana_id')->constrained('campanas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_actividad');
            $table->time('hora_actividad');
            $table->string('lugar');
            $table->integer('cupo_maximo')->nullable();
            $table->string('estado')->default('programada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
