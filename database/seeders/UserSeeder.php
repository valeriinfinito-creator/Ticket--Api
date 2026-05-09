<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Sistema',
            'email'    => 'admin@tickets.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name'     => 'Valeria Ibarra',
            'email'    => 'valeria@tickets.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
