<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('repartidor_id')
                ->nullable()
                ->constrained('repartidores')
                ->nullOnDelete()
                ->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Repartidor::class);
            $table->dropColumn('repartidor_id');
        });
    }
};
