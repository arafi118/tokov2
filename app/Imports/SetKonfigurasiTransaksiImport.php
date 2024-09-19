<?php

namespace App\Imports;

use App\TbJenisTransaksi;
use App\TbKonfigurasiTransaksi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class SetKonfigurasiTransaksiImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        if($row['no'] != null){
            $j_trans = \DB::connection(env('TENANT_DB_CONNECTION'))->table('tb_jenis_transaksis')->where('slug',Str::slug($row['jenis_transaksi']))->first();
            
            $debit   = \DB::connection(env('TENANT_DB_CONNECTION'))->table('tb_rekenings')->where('kode',$row['debit'])->first();

            $kredit  = \DB::connection(env('TENANT_DB_CONNECTION'))->table('tb_rekenings')->where('kode',$row['kredit'])->first();

            //if($debit != null && $kredit !=null && $j_trans !=null){
                return new TbKonfigurasiTransaksi([
                    'tb_jenis_transaksi_id'=>$j_trans->id,
                    'cara_bayar'           =>strtolower($row['cara_bayar']),
                    'rekening_debit_kode'    =>$debit->kode,
                    'rekening_kredit_kode'   =>$kredit->kode   
                ]); 
            //}
            
             
        }
    }
}
