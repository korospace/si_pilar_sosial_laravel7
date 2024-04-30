<?php

use App\Models\UserLevel;
use Illuminate\Database\Seeder;

class UserLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            [
                'id'   => 1,
                'name' => 'superadmin'
            ],
            [
                'id'   => 2,
                'name' => 'verifier'
            ],
            [
                'id'   => 3,
                'name' => 'inputter'
            ],
        ];

        foreach ($levels as $level) {
            UserLevel::firstOrCreate($level);
        }
    }
}
