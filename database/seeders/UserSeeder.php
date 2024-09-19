<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'admin',
                'initial' => 'AT',
                'fullname' => 'Admin Toko',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
                'phone' => '-',
                'company_name' => 'SalePro',
                'role_id' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'staff',
                'initial' => 'ST',
                'fullname' => 'Staff Toko',
                'email' => 'staff@gmail.com',
                'password' => Hash::make('staff'),
                'phone' => '-',
                'company_name' => 'SalePro',
                'role_id' => 2,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'manager',
                'initial' => 'MN',
                'fullname' => 'Manager',
                'email' => 'manager@gmail.com',
                'password' => Hash::make('manager'),
                'phone' => '-',
                'company_name' => 'SalePro',
                'role_id' => 3,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'warehouse',
                'initial' => 'WR',
                'fullname' => 'Warehouse',
                'email' => 'warehouse@gmail.com',
                'password' => Hash::make('warehouse'),
                'phone' => '-',
                'company_name' => 'SalePro',
                'role_id' => 4,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'keuangan',
                'initial' => 'BK',
                'fullname' => 'Bagian Keuangan',
                'email' => 'keuangan@gmail.com',
                'password' => Hash::make('keuangan'),
                'phone' => '-',
                'company_name' => 'SalePro',
                'role_id' => 6,
                'is_active' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        \DB::table('users')->insert($data);
    }
}
