<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Imports\RekeningImport;
use App\Imports\KonfigurasiTransaksiImport;
use Illuminate\Support\Str;
use Excel;

class RekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->importRekening();
       // $this->insertJenisTransaksi();
       // $this->setKonfigurasiTransaksi();
        $this->setKonfigurasiTransaksiByExcel();
    }

    private function importRekening()
    {
        $dir  = 'data/rekening.xlsx';
        Excel::import(new RekeningImport(), public_path($dir));
    }

    private function setKonfigurasiTransaksiByExcel()
    {
        $dir  = 'data/konfigurasi_transaksi.xlsx';
        Excel::import(new KonfigurasiTransaksiImport(), public_path($dir));
    }

    private function insertJenisTransaksi()
    {
        $data = [
            [
                'nama'=>'Penjualan (HPP)',
                'slug'=>Str::slug('Penjualan (HPP)'),
                'deskripsi'=>'POS dan Penjualan'
            ],
            [
                'nama'=>'Laba Penjualan',
                'slug'=>Str::slug('Laba Penjualan'),
                'deskripsi'=>'POS dan Penjualan'
            ],
            [
                'nama'=>'Penjualan (HPP) PO',
                'slug'=>Str::slug('Penjualan (HPP) PO'),
                'deskripsi'=>'POS dan Penjualan'
            ],
            [
                'nama'=>'Laba Penjualan PO',
                'slug'=>Str::slug('Laba Penjualan PO'),
                'deskripsi'=>'POS dan Penjualan'
            ],
            [
                'nama'=>'Retur Penjualan',
                'slug'=>Str::slug('Retur Penjualan'),
                'deskripsi'=>'POS dan Penjualan'
            ],
            [
                'nama'=>'Retur Laba',
                'slug'=>Str::slug('Retur Laba'),
                'deskripsi'=>'POS dan Penjualan'
            ],
            [
                'nama'=>'Pembelian',
                'slug'=>Str::slug('Pembelian'),
                'deskripsi'=>'Pembelian'
            ],
            [
                'nama'=>'Pembelian PO',
                'slug'=>Str::slug('Pembelian PO'),
                'deskripsi'=>'Pembelian'
            ],
            [
                'nama'=>'Retur Pembelian',
                'slug'=>Str::slug('Retur Pembelian'),
                'deskripsi'=>'Retur Pembelian'
            ],
            [
                'nama'=>'Stok Opname Plus',
                'slug'=>Str::slug('Stok Opname Plus'),
                'deskripsi'=>'Stok Opname (SO)'
            ],
            [
                'nama'=>'Stok Opname Minus',
                'slug'=>Str::slug('Stok Opname Minus'),
                'deskripsi'=>'Stok Opname (SO)'
            ]
        ];

        \DB::table('tb_jenis_transaksis')->insert($data);
    }

    private function setKonfigurasiTransaksi()
    {

        $data = [
            [
                'jenis_transaksi'=>'Penjualan (HPP)',
                'cara_bayar'     =>'tunai',
                'debit'          =>'1.1.01.01',
                'kredit'         =>'1.1.03.01'
            ],
            [
                'jenis_transaksi'=>'Laba Penjualan',
                'cara_bayar'     =>'tunai',
                'debit'          =>'1.1.01.01',
                'kredit'         =>'4.1.01.01'
            ],
            [
                'jenis_transaksi'=>'Penjualan (HPP)',
                'cara_bayar'     =>'transfer',
                'debit'          =>'1.1.01.03',
                'kredit'         =>'1.1.03.01'
            ],
            [
                'jenis_transaksi'=>'Laba Penjualan',
                'cara_bayar'     =>'transfer',
                'debit'          =>'1.1.01.03',
                'kredit'         =>'4.1.01.01'
            ],
            [
                'jenis_transaksi'=>'Penjualan (HPP)',
                'cara_bayar'     =>'tempo',
                'debit'          =>'1.1.04.01',
                'kredit'         =>'1.1.03.01'
            ],
            [
                'jenis_transaksi'=>'Laba Penjualan',
                'cara_bayar'     =>'tempo',
                'debit'          =>'1.1.04.01',
                'kredit'         =>'4.1.01.01'
            ],
            [
                'jenis_transaksi'=>'Penjualan (HPP) PO',
                'cara_bayar'     =>'tunai',
                'debit'          =>'1.1.01.01',
                'kredit'         =>'2.1.01.02'
            ],
            [
                'jenis_transaksi'=>'Laba Penjualan PO',
                'cara_bayar'     =>'tunai',
                'debit'          =>'1.1.01.01',
                'kredit'         =>'2.1.01.02'
            ],
            [
                'jenis_transaksi'=>'Penjualan (HPP) PO',
                'cara_bayar'     =>'transfer',
                'debit'          =>'1.1.01.03',
                'kredit'         =>'2.1.01.02'
            ],
            [
                'jenis_transaksi'=>'Laba Penjualan PO',
                'cara_bayar'     =>'transfer',
                'debit'          =>'1.1.01.03',
                'kredit'         =>'2.1.01.02'
            ],
            [
                'jenis_transaksi'=>'Retur Penjualan',
                'cara_bayar'     =>'tunai',
                'debit'          =>'1.1.03.01',
                'kredit'         =>'1.1.01.01'
            ],
            [
                'jenis_transaksi'=>'Retur Laba',
                'cara_bayar'     =>'tunai',
                'debit'          =>'4.1.01.01',
                'kredit'         =>'1.1.01.01'
            ],
            [
                'jenis_transaksi'=>'Pembelian',
                'cara_bayar'     =>'tunai',
                'debit'          =>'1.1.03.01',
                'kredit'         =>'1.1.01.01'
            ],
            [
                'jenis_transaksi'=>'Pembelian',
                'cara_bayar'     =>'transfer',
                'debit'          =>'1.1.03.01',
                'kredit'         =>'1.1.01.03'
            ],
            [
                'jenis_transaksi'=>'Pembelian',
                'cara_bayar'     =>'tempo',
                'debit'          =>'1.1.03.01',
                'kredit'         =>'2.1.01.01'
            ],
            [
                'jenis_transaksi'=>'Pembelian PO',
                'cara_bayar'     =>'tunai',
                'debit'          =>'1.1.04.01',
                'kredit'         =>'1.1.01.01'
            ],
            [
                'jenis_transaksi'=>'Pembelian PO',
                'cara_bayar'     =>'transfer',
                'debit'          =>'1.1.04.01',
                'kredit'         =>'1.1.01.03'
            ],
            [
                'jenis_transaksi'=>'Retur Pembelian',
                'cara_bayar'     =>'tunai',
                'debit'          =>'1.1.01.01',
                'kredit'         =>'1.1.03.01'
            ],
            [
                'jenis_transaksi'=>'Stok Opname Plus',
                'cara_bayar'     =>null,
                'debit'          =>'1.1.03.01',
                'kredit'         =>'4.1.01.03'
            ],
            [
                'jenis_transaksi'=>'Stok Opname Minus',
                'cara_bayar'     =>null,
                'debit'          =>'5.1.09.01',
                'kredit'         =>'1.1.03.01'
            ],
        ];

        foreach ($data as $key => $value) {
            $j_trans = \DB::table('tb_jenis_transaksis')->where('slug',Str::slug($value['jenis_transaksi']))->first();
            
            
            $debit = \DB::table('tb_rekenings')->where('kode',$value['debit'])->first();
            $kredit = \DB::table('tb_rekenings')->where('kode',$value['kredit'])->first();
            
            $det = ['tb_jenis_transaksi_id'=>$j_trans->id,
                    'cara_bayar'           =>$value['cara_bayar'],
                    'rekening_debit_id'    =>$debit->id,
                    'rekening_kredit_id'   =>$kredit->id
                   ];
            
            \DB::table('tb_konfigurasi_transaksis')->insert($det);
        }
    }
}
