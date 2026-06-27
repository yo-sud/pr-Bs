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
            Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            
            $table->string('nombre_empresa');
            $table->string('ruc', 11)->unique()->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('contacto_ejecutivo')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
