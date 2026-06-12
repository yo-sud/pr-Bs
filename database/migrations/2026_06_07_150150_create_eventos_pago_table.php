<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaccion_pago_id')
                ->constrained('transacciones_pago')
                ->cascadeOnDelete();
            $table->uuid('evento_id')->unique();
            $table->string('estado', 20);
            $table->json('payload');
            $table->timestamp('procesado_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_pago');
    }
};
