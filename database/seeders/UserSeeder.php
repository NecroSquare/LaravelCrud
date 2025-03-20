<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'name' => 'member',
                'email' => 'admin@email.com',
                'phone' => '1234567890',
                'address' => 'Member Street',
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}