<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertTaxes();
        // $this->insertProductCategories();
        // $this->insertBrand();
        $this->insertUnits();
        // $this->insertSuppliers();
        // $this->insertProducts();
    }

    private function insertTaxes()
    {
        $data = ['name' => 'PPN', 'rate' => 11, 'is_active' => true];

        \DB::table('taxes')->insert($data);
    }

    private function insertProductCategories()
    {
        $data = [
            [
                'name' => 'Short Pants',
                'is_active' => true,
            ],
            [
                'name' => 'Jogger Pants',
                'is_active' => true,
            ],
            [
                'name' => 'Chino Pants',
                'is_active' => true,
            ],
            [
                'name' => 'Denim Pants',
                'is_active' => true,
            ],
            [
                'name' => 'Shirt',
                'is_active' => true,
            ],
            [
                'name' => 'T-Shirt',
                'is_active' => true,
            ],
            [
                'name' => 'Flannel Shirt',
                'is_active' => true,
            ],
            [
                'name' => 'Coach Jacket',
                'is_active' => true,
            ],
            [
                'name' => 'Sweater',
                'is_active' => true,
            ]
        ];

        \DB::table('categories')->insert($data);
    }

    private function insertBrand()
    {
        $data = [
            [
                'title' => 'Nevada',
                'is_active' => true,
            ],
            [
                'title' => 'Erigo',
                'is_active' => true,
            ],
            [
                'title' => 'Brodo',
                'is_active' => true,
            ],
            [
                'title' => 'Nordhen Basic',
                'is_active' => true,
            ],
            [
                'title' => 'Geoff Max',
                'is_active' => true,
            ],
            [
                'title' => 'Mens Republic',
                'is_active' => true,
            ],
            [
                'title' => 'American Jeans',
                'is_active' => true,
            ],
            [
                'title' => 'Emba',
                'is_active' => true,
            ],
            [
                'title' => 'Carvil',
                'is_active' => true,
            ]
        ];

        \DB::table('brands')->insert($data);
    }

    private function insertUnits()
    {
        $data = [
            [
                'unit_code' => 'pcs',
                'unit_name' => 'Pieces',
                'operator' => '*',
                'operation_value' => 1,
                'is_active' => true
            ]
        ];

        \DB::table('units')->insert($data);
    }

    private function insertSuppliers()
    {
        $data = [
            [
                'name' => 'Rina Tamaki',
                'image' => null,
                'company_name' => 'PT Sakura School Simulator',
                'vat_number' => '9685',
                'email' => 'rina@sss.com',
                'phone_number' => '05898574385',
                'address' => 'Osaka, Japan',
                'city' => 'Osaka',
                'state' => '-',
                'postal_code' => '8695',
                'country' => 'Japan',
                'is_active' => true
            ]
        ];

        \DB::table('suppliers')->insert($data);
    }

    private function insertProducts()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            $cat = \DB::table('categories')->where('id', rand(1, 9))->first();

            $data = [
                'name' => $cat->name . ' ' . $faker->city,
                'code' => rand(1, 99999999),
                'type' => 'standard',
                'barcode_symbology' => 'C128',
                'brand_id' => rand(1, 9),
                'category_id' => $cat->id,
                'unit_id' => 1,
                'purchase_unit_id' => 1,
                'sale_unit_id' => 1,
                'cost' => 75000,
                'price' => 100000,
                'qty' => 0,
                'alert_quantity' => 0,
                'promotion' => 0,
                'promotion_price' => 0,
                'starting_date' => date('Y-m-d'),
                'last_date' => date('Y-m-d'),
                'tax_id' => null,
                'tax_method' => null,
                'image' => $faker->imageUrl(320, 240, 'animals', true),
                'featured' => true,
                'product_details' => $faker->paragraph($nb = 4),
                'is_active' => true,
            ];

            \DB::table('products')->insert($data);
        }
    }
}
