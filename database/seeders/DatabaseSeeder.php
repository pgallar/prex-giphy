<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Prex Giphy',
            'email' => 'prexgiphy@gmail.com',
            'password' => Hash::make('!ragnar121'),
        ]);

        DB::table('oauth_clients')->insert([
            'id' => 1,
            'user_id' => 1,
            'name' => 'Laravel Password Grant Client',
            'secret' => '$2y$10$b6mG1fVf2asJlqXgQsHQH.tGdl3EU/UZ9ESMnw5nDt6P6VckvwEYC',
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => '2024-08-28 16:18:53',
            'updated_at' => '2024-08-28 16:18:53',
        ]);
    }
}
