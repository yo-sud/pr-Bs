<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->upsert(
            [
                [
                    'name' => 'Administrador BookShop',
                    'email' => env('ADMIN_EMAIL', 'admin@bookshop.test'),
                    'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Usuario BookShop',
                    'email' => env('USER_EMAIL', 'user@bookshop.test'),
                    'password' => Hash::make(env('USER_PASSWORD', 'password')),
                    'role' => 'user',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ],
            ['email'],
            ['name', 'password', 'role', 'email_verified_at', 'updated_at'],
        );
    }

    public function down(): void
    {
        DB::table('users')
            ->whereIn('email', [
                env('ADMIN_EMAIL', 'admin@bookshop.test'),
                env('USER_EMAIL', 'user@bookshop.test'),
            ])
            ->delete();
    }
};
