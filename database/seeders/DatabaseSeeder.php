<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'mconsul@schools.ph',
        //     'password' => Hash::make('helios@2025')
        // ]);

        $user = User::find(11);
        // $user->createToken('project-sample')->plainTextToken();
        dd($user->createToken('project-sample')->plainTextToken);

    }
}
