<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'General Medicine', 'meta_title' => 'Medical News', 'meta_description' => 'Updates and insights on general medicine.'],
            ['name' => 'Pediatrics', 'meta_title' => 'Child Health', 'meta_description' => 'Health and wellness for children.'],
            ['name' => 'Cardiology', 'meta_title' => 'Heart Health', 'meta_description' => 'Information on heart diseases and care.'],
            ['name' => 'Dermatology', 'meta_title' => 'Skin Care', 'meta_description' => 'All about skin health.'],
            ['name' => 'Mental Health', 'meta_title' => 'Mind Wellness', 'meta_description' => 'Mental well-being and psychological care.'],
            ['name' => 'Nutrition', 'meta_title' => 'Healthy Eating', 'meta_description' => 'Nutrition advice and dietary tips.'],
        ];

        foreach ($categories as $data) {
            Category::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
            ]);
        }
    }
}
