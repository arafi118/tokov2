<?php

namespace App\Imports;

use App\TbIndukJenisTransaksi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class IndukJenisTransaksiImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        if($row['no'] != null){
          
              return new TbIndukJenisTransaksi([
                'nama'       =>$row['nama'],
                'slug'       =>Str::slug($row['nama']),
            ]);  
        }
    }
}
