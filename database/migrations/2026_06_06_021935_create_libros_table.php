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
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 150);
            $table->string('autor', 100);
            $table->decimal('precio', 8, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->restrictOnDelete();
            $table->foreignId('proveedor_id')
                ->constrained('proveedores')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
