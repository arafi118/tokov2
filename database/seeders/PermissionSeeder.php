<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private $permissionsTree;
    private $permissionsList;
    private $latestId = 1;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }

    private function getPermissionsTreeList()
    {
        $permissions = [
            [
                'menu' => 1,
                'crud' => 0,
                'post' => 0,
                'name' => 'Produk',
                'slug' => 'products',
                'icon' => '<i class="dripicons-list"></i>',
                'submenu' => [
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Kategori',
                        'slug' => 'category.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Produk',
                        'slug' => 'products.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Tambah Produk',
                        'slug' => 'products.create',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Cetak Barcode',
                        'slug' => 'product.printBarcode',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Stok Opname',
                        'slug' => 'qty_adjustment.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Input Stok Opname',
                        'slug' => 'qty_adjustment.create',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Stok',
                        'slug' => 'stock-count.index',
                        'icon' => null,
                    ]
                ]
            ],
            [
                'menu' => 1,
                'crud' => 0,
                'post' => 0,
                'name' => 'Pembelian',
                'slug' => 'purchases',
                'icon' => '<i class="dripicons-card"></i>',
                'submenu' => [
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Pembelian',
                        'slug' => 'purchases.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Tambah Pembelian',
                        'slug' => 'purchases.create',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Impor Pembelian',
                        'slug' => 'purchases.purchase_by_csv',
                        'icon' => null,
                    ]
                ]
            ],
            [
                'menu' => 1,
                'crud' => 0,
                'post' => 0,
                'name' => 'Penjualan',
                'slug' => 'sales',
                'icon' => '<i class="dripicons-cart"></i>',
                'submenu' => [
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Penjualan',
                        'slug' => 'sales.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Tambah Penjualan',
                        'slug' => 'sales.create',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Impor Penjualan',
                        'slug' => 'sales.sale_by_csv',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Gift Card',
                        'slug' => 'gift_cards.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Kupon',
                        'slug' => 'coupons.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Pengiriman',
                        'slug' => 'delivery.index',
                        'icon' => null,
                    ]
                ]
            ],
            [
                'menu' => 1,
                'crud' => 0,
                'post' => 0,
                'name' => 'Pengeluaran',
                'slug' => 'expenses',
                'icon' => '<i class="dripicons-wallet"></i>',
                'submenu' => [
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Kategori',
                        'slug' => 'expense_categories.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Pengeluaran',
                        'slug' => 'expenses.index',
                        'icon' => null,
                    ]
                ]
            ],
            [
                'menu' => 1,
                'crud' => 0,
                'post' => 0,
                'name' => 'Quotation',
                'slug' => 'quotations',
                'icon' => '<i class="dripicons-wallet"></i>',
                'submenu' => [
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Quotation',
                        'slug' => 'quotations.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Tambah Quotation',
                        'slug' => 'quotations.create',
                        'icon' => null,
                    ]
                ],
            ],
            [
                'menu' => 1,
                'crud' => 0,
                'post' => 0,
                'name' => 'Transfer',
                'slug' => 'transfers',
                'icon' => '<i class="dripicons-wallet"></i>',
                'submenu' => [
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Transfer',
                        'slug' => 'transfers.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Tambah Transfer',
                        'slug' => 'transfers.create',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Impor Transfer',
                        'slug' => 'transfers.transfer_by_csv',
                        'icon' => null,
                    ]
                ],
            ],
            [
                'menu' => 1,
                'crud' => 0,
                'post' => 0,
                'name' => 'Retur',
                'slug' => 'return',
                'icon' => '<i class="dripicons-export"></i>',
                'submenu' => [
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Penjualan',
                        'slug' => 'return-sale.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Pembelian',
                        'slug' => 'return-purchase.index',
                        'icon' => null,
                    ]
                ],
            ],
            [
                'menu' => 1,
                'crud' => 0,
                'post' => 0,
                'name' => 'Akunting',
                'slug' => 'return',
                'icon' => '<i class="dripicons-briefcase"></i>',
                'submenu' => [
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Daftar Rekening',
                        'slug' => 'accounts.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Jurnal',
                        'slug' => 'jurnal.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Tutup Buku',
                        'slug' => 'tutup_buku',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Laporan Keuangan',
                        'slug' => 'laporan_keuangan',
                        'icon' => null,
                    ]
                ],
            ],
            [
                'menu' => 1,
                'crud' => 0,
                'post' => 0,
                'name' => 'HRM',
                'slug' => 'hrm',
                'icon' => '<i class="dripicons-user-group"></i>',
                'submenu' => [
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Departmen',
                        'slug' => 'departments.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Employee',
                        'slug' => 'employees.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Attendance',
                        'slug' => 'attendance.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Payroll',
                        'slug' => 'payroll.index',
                        'icon' => null,
                    ],
                    [
                        'menu' => 1,
                        'crud' => 1,
                        'post' => 0,
                        'name' => 'Holiday',
                        'slug' => 'holidays.index',
                        'icon' => null,
                    ]
                ],
                [
                    'menu' => 1,
                    'crud' => 0,
                    'post' => 0,
                    'name' => 'People',
                    'slug' => 'people',
                    'icon' => '<i class="dripicons-user"></i>',
                    'submenu' => [
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Daftar User',
                            'slug' => 'user.index',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Tambah User',
                            'slug' => 'user.create',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Customer',
                            'slug' => 'customer.index',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Tambah Customer',
                            'slug' => 'customer.create',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Toko',
                            'slug' => 'biller.index',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Tambah Toko',
                            'slug' => 'biller.create',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Supplier',
                            'slug' => 'supplier.index',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Tambah Supplier',
                            'slug' => 'supplier.create',
                            'icon' => null,
                        ]
                    ]
                ],
                [
                    'menu' => 1,
                    'crud' => 0,
                    'post' => 0,
                    'name' => 'Pelaporan',
                    'slug' => 'reports',
                    'icon' => '<i class="dripicons-document-remove"></i>',
                    'submenu' => [
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 1,
                            'name' => 'Summary',
                            'slug' => 'report.profitLoss',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Best Seller',
                            'slug' => 'report.best_seller',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 1,
                            'name' => 'Produk',
                            'slug' => 'report.product',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Penjualan (Harian)',
                            'slug' => 'report.daily_sale',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Penjualan (Bulanan)',
                            'slug' => 'report.monthly_sale',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Pembelian (Harian)',
                            'slug' => 'report.daily_purchase',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Pembelian (Bulanan)',
                            'slug' => 'report.monthly_purchase',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 1,
                            'name' => 'Penjualan',
                            'slug' => 'report.sale',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 1,
                            'name' => 'Pembayaran',
                            'slug' => 'report.paymentByDate',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 1,
                            'name' => 'Pembelian',
                            'slug' => 'report.purchase',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Customer',
                            'slug' => '',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 1,
                            'name' => 'Customer Due',
                            'slug' => 'report.customerDueByDate',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Supplier',
                            'slug' => '',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 1,
                            'name' => 'Supplier Due',
                            'slug' => 'report.supplierDueByDate',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Warehouse',
                            'slug' => '',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Warehouse Stok Chart',
                            'slug' => 'report.warehouseStock',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Produk Kadaluarsa',
                            'slug' => 'report.productExpiry',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Produk Qty',
                            'slug' => 'report.qtyAlert',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Penjualan Harian Obyektif',
                            'slug' => 'report.dailySaleObjective',
                            'icon' => null,
                        ]
                    ]
                ],
                [
                    'menu' => 1,
                    'crud' => 0,
                    'post' => 0,
                    'name' => 'Pengaturan',
                    'slug' => 'settings',
                    'icon' => '<i class="dripicons-gear"></i>',
                    'submenu' => [
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Daftar User',
                            'slug' => 'user.index',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Tambah User',
                            'slug' => 'user.create',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Customer',
                            'slug' => 'customer.index',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Tambah Customer',
                            'slug' => 'customer.create',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Toko',
                            'slug' => 'biller.index',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Tambah Toko',
                            'slug' => 'biller.create',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Supplier',
                            'slug' => 'supplier.index',
                            'icon' => null,
                        ],
                        [
                            'menu' => 1,
                            'crud' => 1,
                            'post' => 0,
                            'name' => 'Tambah Supplier',
                            'slug' => 'supplier.create',
                            'icon' => null,
                        ]
                    ],
                ]
            ]
        ];

        $this->permissionsTree = $permissions;
    }
}
