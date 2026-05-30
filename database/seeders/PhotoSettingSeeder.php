<?php

namespace Database\Seeders;

use App\Models\PhotoSetting;
use Illuminate\Database\Seeder;

class PhotoSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'name'         => 'Comic',
                'gmic_command' => 'cl_comic 4,1,0,0,1,15,15,1,10,20,6,2,0,0,0,0,0,0,50,50',
                'sort_order'   => 1,
                'is_active'    => true,
            ],
            [
                'name'         => 'Schwarzweiß',
                'gmic_command' => '-to_gray',
                'sort_order'   => 2,
                'is_active'    => true,
            ],
            [
                'name'         => 'Weichzeichner',
                'gmic_command' => '-blur 4 -sharpen 80',
                'sort_order'   => 3,
                'is_active'    => true,
            ],
        ];

        foreach ($settings as $setting) {
            PhotoSetting::firstOrCreate(['name' => $setting['name']], $setting);
        }
    }
}
