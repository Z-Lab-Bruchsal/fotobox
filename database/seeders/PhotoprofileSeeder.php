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
                'name' => 'Comic',
                'active' => true,
                'commands' => 'simplelocalcontrast_p 16,2,0,1,1,1,1,1,1,1,1,0
cl_comic 4,1,0,0,1,15,15,1,10,20,6,2,0,0,0,0,0,0,50,50',
            ],
            [
                'name' => 'Edges',
                'active' => true,
                'commands' => 'fx_canny 5,0.05,0.15,1',
            ],
            [
                'name' => 'Tron',
                'active' => true,
                'commands' => 'samj_chalkitup 5,2.5,1.5,50,1,5,5,0,0,7,0.8,1.9,7,0',
            ],
            [
                'name' => 'Warhol',
                'active' => true,
                'commands' => 'warhol 3,3,2,40',
            ],
            [
                'name' => 'SW-Comic',
                'active' => true,
                'commands' => 'simplelocalcontrast_p 16,2,0,1,1,1,1,1,1,1,1,0
cl_lineart 0,0,2,1,15,15,1,0,6,2,2,0,0,0,50,50',
            ],
            [
                'name' => 'Neon',
                'active' => true,
                'commands' => 'fx_gradient2rgb 0.58,0,5.8,0,0',
            ],
            [
                'name' => 'Neon 2',
                'active' => true,
                'commands' => 'fx_gcd_canny 1,0.05,0.15',
            ]
        ];

        foreach ($profiles as $profile) {
            Photoprofile::firstOrCreate(['name' => $profile['name']], $profile);
        }
    }
}
