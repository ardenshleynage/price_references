<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /* User::factory()->create([ */
        /*     'name' => 'Test User', */
        /*     'email' => 'test@example.com', */
        /* ]); */
        /**/
        User::create([
            'username' => 'an',
            'email' => 'nagebenshley160503istheking@gmail.com',
            'password' => Hash::make('1234'),
            'role' => 1,
            'status' => 1,
        ]);
    }
}
