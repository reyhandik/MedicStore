<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            [
                'category_id' => 1,
                'name' => 'Aspirin 500mg',
                'description' => 'Effective pain reliever and fever reducer',
                'price' => 15000,
                'stock' => 50,
                'needs_recipe' => false,
            ],
            [
                'category_id' => 1,
                'name' => 'Ibuprofen 400mg',
                'description' => 'Anti-inflammatory pain relief',
                'price' => 18000,
                'stock' => 45,
                'needs_recipe' => false,
            ],
            [
                'category_id' => 2,
                'name' => 'Paracetamol 500mg',
                'description' => 'Fever and headache relief',
                'price' => 12000,
                'stock' => 60,
                'needs_recipe' => false,
            ],
            [
                'category_id' => 3,
                'name' => 'Amoxicillin 500mg',
                'description' => 'Antibiotic for bacterial infections',
                'price' => 45000,
                'stock' => 30,
                'needs_recipe' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Ciprofloxacin 500mg',
                'description' => 'Broad-spectrum antibiotic',
                'price' => 55000,
                'stock' => 25,
                'needs_recipe' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'Vitamin C 1000mg',
                'description' => 'Immune system support',
                'price' => 25000,
                'stock' => 100,
                'needs_recipe' => false,
            ],
            [
                'category_id' => 4,
                'name' => 'Vitamin D 1000IU',
                'description' => 'Calcium absorption support',
                'price' => 30000,
                'stock' => 80,
                'needs_recipe' => false,
            ],
            [
                'category_id' => 5,
                'name' => 'Hydrocortisone Cream',
                'description' => 'Skin inflammation reliever',
                'price' => 35000,
                'stock' => 40,
                'needs_recipe' => false,
            ],
            [
                'category_id' => 6,
                'name' => 'Antacid Tablet',
                'description' => 'Heartburn relief',
                'price' => 8000,
                'stock' => 120,
                'needs_recipe' => false,
            ],
            [
                'category_id' => 2,
                'name' => 'Cough Syrup',
                'description' => 'Effective cough suppressant',
                'price' => 20000,
                'stock' => 70,
                'needs_recipe' => false,
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
