<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Super Admins
         * ==============
         */
        $admins = [
            [
                'email'    => 'admin1@tes.com',
                'password' => Crypt::encrypt('admin1'),
                'name'     => 'admin 1',
                'level_id' => 1,
            ],
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate($admin);
        }

        /**
         * Verifiers
         * ==============
         */
        $verifiers = [
            [
                'email'    => 'verifikator1@tes.com',
                'password' => Crypt::encrypt('verifikator1'),
                'name'     => 'verifikator 1',
                'level_id' => 2,
                'site_id'  => 1,
            ],
            [
                'email'    => 'verifikator2@tes.com',
                'password' => Crypt::encrypt('verifikator2'),
                'name'     => 'verifikator 2',
                'level_id' => 2,
                'site_id'  => 2,
            ],
        ];

        foreach ($verifiers as $verifier) {
            User::firstOrCreate($verifier);
        }

        /**
         * Inputters
         * ==============
         */
        $inputters = [
            [
                'email'    => 'inputter1@tes.com',
                'password' => Crypt::encrypt('inputter1'),
                'name'     => 'inputter 1',
                'level_id' => 3,
                'site_id'  => 1,
            ],
            [
                'email'    => 'inputter2@tes.com',
                'password' => Crypt::encrypt('inputter2'),
                'name'     => 'inputter 2',
                'level_id' => 3,
                'site_id'  => 2,
            ],
        ];

        foreach ($inputters as $inputter) {
            User::firstOrCreate($inputter);
        }
    }
}
