<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas =  [
            [
                'name'          => 'Administrator',
                'email'         => 'admin@gmail.com',
                'password'      => bcrypt('admin123'),
                'created_at'    => Carbon::now()
            ],
        ];

        \DB::table('admins')->insert($datas);
    }
}
