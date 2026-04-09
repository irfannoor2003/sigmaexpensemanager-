<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $categories = ['Bilty', 'Cash', 'Office Supplies/Expenses', 'Mobile Load','Food/Entertainment','Mis Salary','Parking','Water Bottle Refill','Miscellaneous' ];
    foreach($categories as $cat){
        \App\Models\ExpenseCategory::create(['name' => $cat]);
    }
}
}
