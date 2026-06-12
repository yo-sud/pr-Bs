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
            ->where('role', 'cliente')
            ->update(['role' => 'user']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('user')->change();
        });
    }

    public function down(): void
    {
        // La aplicacion solo admite los roles user y admin.
    }
};
