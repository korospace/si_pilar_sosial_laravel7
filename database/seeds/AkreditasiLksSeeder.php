<?php

use App\Models\AkreditasiLks;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkreditasiLksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            [
                'name' => 'Akreditasi A'
            ],
            [
                'name' => 'Akreditasi B'
            ],
            [
                'name' => 'Akreditasi C'
            ],
            [
                'name' => 'Belum'
            ],
        ];

        foreach ($rows as $row) {
            AkreditasiLks::firstOrCreate($row);
        }
    }
}
