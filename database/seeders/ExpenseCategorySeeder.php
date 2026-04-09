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
    $categories = ['Bilty', 'Cash', 'Office Supplies/Expenses', 'Misc/Mobile Load','Food/Entertainment','Salary','Parking','Water Bottle Refill','Other' ];
    foreach($categories as $cat){
        \App\Models\ExpenseCategory::create(['name' => $cat]);
    }
}
}
