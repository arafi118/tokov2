<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setGeneralSetting();
        $this->insertCustomerGroups();
        $this->insertCustomers();
        $this->insertWarehouses();
        $this->insertBillers();
        $this->insertCurrencies();
        $this->setPosSetting();
        $this->setRewardPointSetting();
    }

    private function setGeneralSetting()
    {
        $data = [
            'id' => 1,
            'site_title' => 'SalePro',
            'site_logo' => '20220905125905.png',
            'subdomain' => null,
            'currency' => 1,
            'staff_access' => 'own',
            'date_format' => 'd/m/Y',
            'theme' => 'default.css',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'currency_position' => 'prefix',
            'tgl_awal_app' => '2023-09-01',
            'tahun_saldo_awal_tahun' => null,
            'bulan_saldo_awal_tahun' => null,
            'tahun_saldo_bulan_lalu' => null,
            'bulan_saldo_bulan_lalu' => null
        ];

        \DB::table('general_settings')->insert($data);
    }

    private function insertCustomerGroups()
    {
        $data = [
            [
                'name' => 'general',
                'percentage' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),

            ],
            [
                'name' => 'distributor',
                'percentage' => -10,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'reseller',
                'percentage' => 5,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),

            ]
        ];

        \DB::table('customer_groups')->insert($data);
    }

    private function insertCustomers()
    {
        $group = \DB::table('customer_groups')->where('name', 'general')->first();
        $data  = [
            'name' => 'walk-in-customer',
            'phone_number' => '-',
            'tax_no' => '-',
            'address' => '-',
            'city' => '-',
            'state' => '-',
            'postal_code' => '-',
            'country' => '-',
            'deposit' => 0,
            'expense' => 0,
            'is_active' => 1,
            'customer_group_id' => $group->id
        ];

        \DB::table('customers')->insert($data);
    }

    private function insertWarehouses()
    {
        $data = [
            'name' => 'Warehouse 1',
            'phone' => '-',
            'email' => '-',
            'address' => '-',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        \DB::table('warehouses')->insert($data);
    }

    private function insertBillers()
    {
        $data = [
            'name' => 'Toko Kita',
            'image' => '-',
            'company_name' => 'Toko Kita',
            'vat_number' => '000',
            'email' => 'admin@tokokita',
            'phone_number' => '0',
            'address' => '-',
            'city' => '-',
            'state' => '-',
            'postal_code' => '-',
            'country' => '-',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        \DB::table('billers')->insert($data);
    }

    private function insertCurrencies()
    {
        $data = [
            'name' => 'Rupiah',
            'code' => 'Rp',
            'exchange_rate' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        \DB::table('currencies')->insert($data);
    }

    private function setPosSetting()
    {
        $customer   = \DB::table('customers')->where('name', 'walk-in-customer')->first();
        $warehouse  = \DB::table('warehouses')->where('name', 'Warehouse 1')->first();
        $biller     = \DB::table('billers')->where('id', '1')->first();

        $data = [
            'id' => 1,
            'customer_id' => $customer->id,
            'warehouse_id' => $warehouse->id,
            'biller_id'   => $biller->id,
            'product_number' => 4,
            'keybord_active' => 0,
            'stripe_public_key' => 'tokokita_3fd170f0532a72007dde7789b120bde1',
            'stripe_secret_key' => 'tokokita_1a0833bb876654f523d400e288bc3630',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        \DB::table('pos_setting')->insert($data);
    }

    private function setRewardPointSetting()
    {
        $data = [
            'id' => 1,
            'per_point_amount' => 300,
            'minimum_amount' => 1000,
            'duration' => 1,
            'type' => 'year',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        \DB::table('reward_point_settings')->insert($data);
    }
}
