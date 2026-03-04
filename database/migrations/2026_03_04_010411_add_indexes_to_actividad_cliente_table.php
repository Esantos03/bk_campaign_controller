<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actividad_cliente', function (Blueprint $table) {
            $table->index(['cliente_id', 'estado_confirmacion'], 'idx_cliente_estado');
            $table->index(['actividad_id', 'estado_confirmacion'], 'idx_actividad_estado');
            $table->index('fecha_confirmacion');
        });
    }

    public function down(): void
    {
        Schema::table('actividad_cliente', function (Blueprint $table) {
            $table->dropIndex('idx_cliente_estado');
            $table->dropIndex('idx_actividad_estado');
            $table->dropIndex(['fecha_confirmacion']);
        });
    }
};
