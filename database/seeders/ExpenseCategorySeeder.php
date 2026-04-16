<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'bilty',
            'cash',
            'office_supplies_expenses',
            'mobile_load',
            'food_entertainment',
            'mis_salary',
            'parking',
            'water_bottle_refill',
            'miscellaneous',
            'freight_out',
        ];

        foreach ($categories as $cat) {
            ExpenseCategory::create([
                'name' => $cat
            ]);
        }
    }
}
