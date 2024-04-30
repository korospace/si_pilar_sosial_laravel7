<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RegionSeeder::class,
            EducationSeeder::class,
            BankSeeder::class,
            SiteSeeder::class,
            UserLevelSeeder::class,
            UserSeeder::class,
            LayananLksSeeder::class,
            AkreditasiLksSeeder::class,
        ]);
    }
}
