<?php

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $educations = [
            [
                'name' => 'SD'
            ],
            [
                'name' => 'SLTP'
            ],
            [
                'name' => 'SLTA'
            ],
            [
                'name' => 'D3'
            ],
            [
                'name' => 'S1'
            ],
            [
                'name' => 'S2'
            ]
        ];

        foreach ($educations as $education) {
            Education::firstOrCreate($education);
        }
    }
}
