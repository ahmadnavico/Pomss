<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'post-content-limit', 'value' => '1000'],
            ['key' => 'post-links-limit', 'value' => '5'],
            ['key' => 'post-description-limit', 'value' => '255'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
