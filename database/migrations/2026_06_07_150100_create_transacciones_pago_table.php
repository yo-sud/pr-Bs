<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacciones_pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->uuid('referencia')->unique();
            $table->decimal('monto', 10, 2);
            $table->char('moneda', 3)->default('PEN');
            $table->string('estado', 20)->default('pendiente')->index();
            $table->json('payload')->nullable();
            $table->timestamp('procesado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones_pago');
    }
};
