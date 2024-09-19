<?php

namespace App\Imports;

use App\TbRekening;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class RekeningImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $parent = TbRekening::where('kode',$row['parent'])->first();

        if($row['no'] != null){
              return new TbRekening([
                'kode'       =>$row['kode'],
                'nama'       =>$row['nama'],
                'slug'       =>Str::slug($row['nama']),
                'parent_id'  =>$parent !=null ? $parent->id : 0,
                'depth'      =>$row['depth'],
                'jenis_mutasi'   =>$row['jenis_mutasi'] == 'D' ? 'debit' : 'kredit',
                'no_rek_bank'    =>$row['no_rek_bank'] !='' && $row['no_rek_bank'] !=null ? $row['no_rek_bank'] : null,
                'atas_nama_rek'  =>$row['atas_nama_rek'] !='' && $row['atas_nama_rek'] !=null ? $row['atas_nama_rek'] : null,
                'kategori'       =>$row['kategori']
            ]);  
        }
    }
}
