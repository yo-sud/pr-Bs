<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->whereIn('role', ['cliente', 'repartidor'])
            ->update(['role' => 'user']);

        if (Schema::hasColumn('pedidos', 'repartidor_id')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dropConstrainedForeignId('repartidor_id');
            });
        }

        if (Schema::hasColumn('pedidos', 'asignado_at')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dropColumn('asignado_at');
            });
        }
    }

    public function down(): void
    {
    }
};
