<?php

namespace Database\Seeders;

use App\Models\Photoprofile;
use Illuminate\Database\Seeder;

class PhotoprofileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'name' => 'Standard',
                'active' => true,
                'commands' => 'simplelocalcontrast_p 16,2,0,1,1,1,1,1,1,1,1,0
cl_comic 4,1,0,0,1,15,15,1,10,20,6,2,0,0,0,0,0,0,50,50',
            ],
            [
                'name' => 'edges',
                'active' => true,
                'commands' => 'fx_canny 5,0.05,0.15,1',
            ],
            [
                'name' => 'tron',
                'active' => true,
                'commands' => 'samj_chalkitup 5,2.5,1.5,50,1,5,5,0,0,7,0.8,1.9,7,0',
            ]
        ];

        foreach ($profiles as $profile) {
            Photoprofile::firstOrCreate(['name' => $profile['name']], $profile);
        }
    }
}
