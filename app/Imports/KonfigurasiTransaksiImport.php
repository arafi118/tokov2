<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\JenisTransaksiImport;
use App\Imports\IndukJenisTransaksiImport;
use App\Imports\SetKonfigurasiTransaksiImport;

class KonfigurasiTransaksiImport implements WithMultipleSheets 
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
        return [
            'Induk Jenis Transaksi' => new IndukJenisTransaksiImport(),
            'Jenis Transaksi' => new JenisTransaksiImport(),
            'Set Konfigurasi Transaksi' => new SetKonfigurasiTransaksiImport(),
            'Konfigurasi Nota'=> new KonfigurasiNotaImport()
        ];
    }
}
