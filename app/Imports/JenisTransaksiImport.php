<?php

namespace App\Imports;

use App\TbJenisTransaksi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class JenisTransaksiImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        if($row['no'] != null){
          $ijt = \DB::connection(env('TENANT_DB_CONNECTION'))->table('tb_induk_jenis_transaksis')->where('slug',Str::slug($row['induk']))->first();
         
              return new TbJenisTransaksi([
                'tb_induk_jenis_transaksi_id'=>$ijt->id,
                'nama'       =>$row['nama'],
                'slug'       =>Str::slug($row['nama']),
                'deskripsi'  =>$row['deskripsi']
            ]);  
        }
    }
}
