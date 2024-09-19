<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function __construct()
    {
        $this->gperms = ['products-edit',
        'products-delete',
        'products-add',
        'products-index',
        'purchases-index',
        'purchases-add',
        'purchases-edit',
        'purchases-delete',
        'sales-index',
        'sales-add',
        'sales-edit',
        'sales-delete',
        'sale-payment-index',
        'sale-payment-add',
        'sale-payment-edit',
        'sale-payment-delete',
        'quotes-index',
        'quotes-add',
        'quotes-edit',
        'quotes-delete',
        'transfers-index',
        'transfers-add',
        'transfers-edit',
        'transfers-delete',
        'returns-index',
        'returns-add',
        'returns-edit',
        'returns-delete',
        'customers-index',
        'customers-add',
        'customers-edit',
        'customers-delete',
        'suppliers-index',
        'suppliers-add',
        'suppliers-edit',
        'suppliers-delete',
        'product-report',
        'purchase-report',
        'sale-report',
        'customer-report',
        'due-report',
        'users-index',
        'users-add',
        'users-edit',
        'users-delete',
        'profit-loss',
        'best-seller',
        'daily-sale',
        'monthly-sale',
        'daily-purchase',
        'monthly-purchase',
        'payment-report',
        'warehouse-stock-report',
        'product-qty-alert',
        'supplier-report',
        'expenses-index',
        'expenses-add',
        'expenses-edit',
        'expenses-delete',
        'general_setting',
        'mail_setting',
        'pos_setting',
        'hrm_setting',
        'reward_point_setting',
        'today_profit',
        'today_sale',
        'purchase-return-index',
        'purchase-return-add',
        'purchase-return-edit',
        'purchase-return-delete',
        'purchase-payment-index',
        'purchase-payment-add',
        'purchase-payment-edit',
        'purchase-payment-delete',
        'account-index',
        'balance-sheet',
        'account-statement',
        'account-report',
        'account-journal',
        'account-close-statement',
        'department',
        'attendance',
        'payroll',
        'employees-index',
        'employees-add',
        'employees-edit',
        'employees-delete',
        'user-report',
        'stock_count',
        'adjustment',
        'sms_setting',
        'create_sms',
        'print_barcode',
        'empty_database',
        'customer_group',
        'unit',
        'tax',
        'gift_card',
        'coupon',
        'holiday',
        'warehouse-report',
        'warehouse',
        'brand',
        'billers-index',
        'billers-add',
        'billers-edit',
        'billers-delete',
        'money-transfer',
        'category',
        'delivery',
        'discount',
        'discount_plan'
    ];

        $this->sperms = [
            'products-add',
            'products-index',
            'purchases-index',
            'purchases-add',
            'purchase-payment-index',
            'purchase-payment-add',
            'sales-index',
            'sales-add',
            'sale-payment-index',
            'sale-payment-add',
            'transfers-index',
            'transfers-add',
            'returns-index',
            'returns-add',
            'customers-index',
            'customers-add',
            'purchase-return-index',
            'purchase-return-add',
            'discount',
            'discount_plan',
            'reward_point_setting',
            'today_profit',
            'today_sale',
        ];
    }

    public function run()
    {
        $this->insertRoles();
        $this->insertPermissions();
        $this->createAdmin();
        $this->createStaff();
    }

    private function createAdmin()
    {
        $data = array();
        foreach ($this->gperms as $key => $value) {
            $permissions = \DB::table('permissions')->where('name',$value)->first();
            $role = \DB::table('roles')->where('name','Administrator')->first();

            $data[] = ['role_id'=>$role->id,
                     'permission_id'=>$permissions->id
                    ];
        }

        \DB::table('role_has_permissions')->insert($data);
        
    }

    private function createStaff()
    {
        $data = array();
        foreach ($this->sperms as $key => $value) {
            $permissions = \DB::table('permissions')->where('name',$value)->first();
            $role = \DB::table('roles')->where('name','Staff')->first();

            $data[] = ['role_id'=>$role->id,
                     'permission_id'=>$permissions->id
                    ];
        }

        \DB::table('role_has_permissions')->insert($data);
        
    }

    private function insertRoles()
    {
        $data = [
            ['name'       =>'Administrator',
             'description'=>'Admin can access all data',
             'guard_name' =>'web',
             'is_active'  =>1,
            ],
            ['name'       =>'Staff',
             'description'=>'Staff has specific access...',
             'guard_name' =>'web',
             'is_active'  =>1,
            ],
            ['name'       =>'Manager',
             'description'=>'manager of store',
             'guard_name' =>'web',
             'is_active'  =>1,
            ],
            ['name'       =>'Warehouse',
             'description'=>'Warehouse staff',
             'guard_name' =>'web',
             'is_active'  =>1,
            ],
            ['name'       =>'Customer',
             'description'=>'Customer',
             'guard_name' =>'web',
             'is_active'  =>1,
            ],
            ['name'       =>'Keuangan',
             'description'=>'Customer',
             'guard_name' =>'web',
             'is_active'  =>1,
            ]

        ];

        \DB::table('roles')->insert($data);
    } 

    private function insertPermissions()
    {
        $perms = $this->gperms;

        $data = array();
        foreach ($perms as $key => $value) {
            $data[] = ['name'=>$value,
                       'guard_name'=>'web'];
        }

        \DB::table('permissions')->insert($data);
        
    }

}
