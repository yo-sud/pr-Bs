<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('libros', 'isbn')) {
            return;
        }

        Schema::table('libros', function (Blueprint $table) {
            $table->string('isbn', 20)->nullable()->unique()->after('id');
            $table->text('descripcion')->nullable()->after('autor');
            $table->string('editorial', 100)->nullable()->after('descripcion');
            $table->date('fecha_publicacion')->nullable()->after('editorial');
            $table->string('portada')->nullable()->after('fecha_publicacion');
            $table->string('estado', 20)->default('activo')->index()->after('stock');
            $table->boolean('destacado')->default(false)->index()->after('estado');
            $table->unsignedInteger('ventas')->default(0)->index()->after('destacado');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('libros', 'isbn')) {
            return;
        }

        Schema::table('libros', function (Blueprint $table) {
            $table->dropColumn([
                'isbn',
                'descripcion',
                'editorial',
                'fecha_publicacion',
                'portada',
                'estado',
                'destacado',
                'ventas',
            ]);
        });
    }
};
