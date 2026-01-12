<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Pain Relief', 'description' => 'Medications for pain management'],
            ['name' => 'Cold & Flu', 'description' => 'Cold and flu remedies'],
            ['name' => 'Antibiotics', 'description' => 'Prescription antibiotics'],
            ['name' => 'Vitamins', 'description' => 'Vitamin supplements'],
            ['name' => 'Skin Care', 'description' => 'Topical skin treatments'],
            ['name' => 'Digestive', 'description' => 'Digestive system remedies'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
