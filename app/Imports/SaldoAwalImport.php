<?php

namespace App\Imports;

use App\TbRekeningSaldo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SaldoAwalImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function __construct($bulan,$tahun,$warehouse_id)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->warehouse_id = $warehouse_id;
    }
    public function model(array $row)
    {
        if($row['no'] != null){
            $cek = TbRekeningSaldo::where('tb_rekening_kode',$row['kode'])
                                  ->where('tahun',$this->tahun)
                                  ->where('warehouse_id',$this->warehouse_id)
                                  ->first();

            $data = ['tb_rekening_kode'     =>$row['kode'],
                     'tahun'                =>$this->tahun,
                     'warehouse_id'         =>$this->warehouse_id,
                     'debit_'.$this->bulan  =>$row['debit'] != null ? $row['debit'] : 0,
                     'kredit_'.$this->bulan =>$row['kredit'] != null ? $row['kredit'] : 0
                    ];

            if($cek !=null){
                TbRekeningSaldo::where('tb_rekening_kode',$row['kode'])
                                  ->where('tahun',$this->tahun)
                                  ->where('warehouse_id',$this->warehouse_id)
                                  ->update($data);
            }else{
                TbRekeningSaldo::insert($data);
            }
        }
    }
}
