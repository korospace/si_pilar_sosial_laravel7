<?php

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = ['provinsi','kabupaten-kota','kecamatan','kelurahan'];

        $csvFile = fopen(base_path("database/data/regions_permendagri-72-201.csv"), "r");

        while (($data = fgetcsv($csvFile, 92000, ",")) !== FALSE) {
            $splitId = explode(".", $data['0']);

            Region::firstOrCreate([
                "id"        => $data['0'],
                "prov_id"   => $splitId[0],
                "kab_id"    => isset($splitId[1]) ? $splitId[1] : null,
                "kec_id"    => isset($splitId[2]) ? $splitId[2] : null,
                "kel_id"    => isset($splitId[3]) ? $splitId[3] : null,
                "name"      => $data['1'],
                "type"      => isset($type[count($splitId)-1]) ? $type[count($splitId)-1] : null,
            ]);
        }

        fclose($csvFile);
    }
}
