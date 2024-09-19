<?php

namespace App\Imports;

use App\Nota;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class KonfigurasiNotaImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        if($row['no'] != null){
              return new Nota([
                'jenis_transaksi' =>Str::slug($row['jenis_transaksi']),
                'kode'            =>$row['kode'],
                'table'           =>$row['table'],
            ]);  
        }
    }
}
