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
        Schema::table('repartidores', function (Blueprint $table) {
            $table->string('contacto_ejecutivo')->nullable()->after('nombre_empresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repartidores', function (Blueprint $table) {
            $table->dropColumn('contacto_ejecutivo');
        });
    }
};
