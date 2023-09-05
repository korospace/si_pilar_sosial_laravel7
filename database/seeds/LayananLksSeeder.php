<?php

use App\Models\LayananLks;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LayananLksSeeder extends Seeder
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
                'name' => 'Anak'
            ],
            [
                'name' => 'Napza'
            ],
            [
                'name' => 'Lansia'
            ],
            [
                'name' => 'Disabilitas'
            ],
            [
                'name' => 'Rumah Singgah'
            ],
            [
                'name' => 'Taman Anak Sejahtera'
            ],
            [
                'name' => 'Anak Berhadapan dengan Hukum'
            ],
            [
                'name' => 'Penanganan Korban Bencana'
            ],
            [
                'name' => 'Lain-lain'
            ]
        ];

        foreach ($rows as $row) {
            LayananLks::firstOrCreate($row);
        }
    }
}
