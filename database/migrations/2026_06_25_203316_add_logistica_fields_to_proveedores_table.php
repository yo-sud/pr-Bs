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
        Schema::table('proveedores', function (Blueprint $table) {
            $table->unsignedInteger('tiempo_entrega_dias')->default(5)->after('nombre_empresa');
            $table->decimal('costo_envio', 8, 2)->default(0.00)->after('tiempo_entrega_dias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn(['tiempo_entrega_dias', 'costo_envio']);
        });
    }
};
