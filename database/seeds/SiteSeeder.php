<?php

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = [
            [
                'region_id' => "31.01",
                'name' => 'KAB. ADM. KEP. SERIBU',
            ],
            [
                'region_id' => "31.71",
                'name' => 'JAKARTA PUSAT',
            ],
            [
                'region_id' => "31.72",
                'name' => 'JAKARTA UTARA'
            ],
            [
                'region_id' => "31.73",
                'name' => 'JAKARTA BARAT'
            ],
            [
                'region_id' => "31.74",
                'name' => 'JAKARTA SELATAN'
            ],
            [
                'region_id' => "31.75",
                'name' => 'JAKARTA TIMUR'
            ],
        ];

        foreach ($sites as $site) {
            Site::firstOrCreate($site);
        }
    }
}
