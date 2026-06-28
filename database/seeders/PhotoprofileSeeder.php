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
            ]
        ];

        foreach ($profiles as $profile) {
            Photoprofile::firstOrCreate(['name' => $profile['name']], $profile);
        }
    }
}
