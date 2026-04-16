<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['role' => 'admin'],
            [
                'name' => 'Hunzla Malik',
                'name_ur' => 'ہنزلا ملک',
                'pin' => Hash::make('12345'),
                'role' => 'admin',
                'wallet' => 0
            ]
        );

        User::updateOrCreate(
            ['role' => 'hr'],
            [
                'name' => 'Moazam Shahdi',
                'name_ur' => 'معاذم شاہد',
                'pin' => Hash::make('11111'),
                'role' => 'hr',
                'wallet' => 0
            ]
        );

        User::updateOrCreate(
            ['role' => 'expense_manager'],
            [
                'name' => 'Arif Hussain',
                'name_ur' => 'عارف حسین',
                'pin' => Hash::make('22222'),
                'role' => 'expense_manager',
                'wallet' => 0
            ]
        );
    }
}
