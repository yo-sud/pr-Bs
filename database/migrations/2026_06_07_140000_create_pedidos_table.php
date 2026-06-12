<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('direccion');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('envio', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('estado_pago', 20)->default('pendiente')->index();
            $table->string('estado_pedido', 20)->default('pendiente')->index();
            $table->timestamp('pagado_at')->nullable();
            $table->timestamp('enviado_at')->nullable();
            $table->timestamp('entregado_at')->nullable();
            $table->timestamp('cancelado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
