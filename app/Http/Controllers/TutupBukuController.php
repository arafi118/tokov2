<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use App\TbRekeningSaldo;
use App\TbJurnal;
use App\TbJurnalDetail;
use App\TbIndukJenisTransaksi;
use App\TbJenisTransaksi;
use Carbon\Carbon;

class TutupBukuController extends Controller
{
    public function __construct()
    {
        $this->jurnal = new TbJurnal;
    }

    public function index(Request $request)
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();

        return view('backend.tutup_buku.index',compact('lims_warehouse_list'));
    }

    public function submit(Request $req)
    {
       \DB::beginTransaction();

       try {
            $cek_exist = TbJurnal::where('warehouse_id',$req->warehouse_id)
                                 ->whereYear('tgl_transaksi',$req->tahun)
                                 ->first();

            $get_induk = TbIndukJenisTransaksi::where('nama','Tutup Buku Tahunan')->first();

            if($cek_exist != null){
                $jurnal = $cek_exist;
            }else{
                $jurnal = new TbJurnal;
            }

            $no_trans   = $this->jurnal->notaCounter('input_jurnal');

            $jurnal->warehouse_id     = $req->warehouse_id;
            $jurnal->tb_induk_jenis_transaksi_id = $get_induk->id;
            $jurnal->tgl_transaksi    = $req->tahun.'-'.date('m').'-'.date('d');
            $jurnal->tabel_transaksi  = 'jurnals';
            $jurnal->nomor_transaksi  = $no_trans;
            $jurnal->memo             = 'Tutup Buku Tahun '.Carbon::parse($req->tgl_transaksi)->format('Y');
            $jurnal->insertedby       = auth()->user()->id;
            $jurnal->save();

            $arr_in = ['Beban','Pendapatan'];

            if($cek_exist != null){
                TbJurnalDetail::where('tb_jurnal_id',$jurnal->id)->delete();
            }

            foreach($arr_in as $a){
                $jenis = TbJenisTransaksi::where('nama','Jurnal Tutup '.$a)->first();

                $list_trans = $this->queryTutupBuku($req->warehouse_id,$req->tahun,$a);

                foreach ($list_trans as $key => $value) {
                    $detail = new TbJurnalDetail;
                    $detail->tb_jurnal_id  = $jurnal->id;
                    $detail->tb_jenis_transaksi_id = $jenis->id;
                    $detail->debit_kode     = $a == 'Beban' ? $value['kode'] : '3.2.01.01';
                    $detail->kredit_kode    = $a == 'Pendapatan' ? $value['kode']  :'3.2.01.01';
                    $detail->debit_nominal  = $value['saldo'];
                    $detail->kredit_nominal = $value['saldo'];
                    $detail->deskripsi      = 'Jurnal Tutup '.$a;
                    $detail->save();
                }

            }

            $params  = ['debit_kode'         =>'3.2.01.01',
                        'debit_jenis_mutasi' =>'',
                        'kredit_kode'        =>'3.2.01.01',
                        'kredit_jenis_mutasi'=>'',
                        'gudang'             =>$req->warehouse_id,
                        'tanggal_transaksi'  =>$req->tahun.'-'.date('m').'-'.date('d')
                       ];

            $this->jurnal->updateSaldo($params);                    
            $this->jurnal->updateLabaRugi($params);    

       } catch (\Exception $e) {
            \DB::rollback();
            \Log::error("tutup buku submit : {$e->getMessage()}");

            $response = false;
            return abort(500);
       }

       \DB::commit();

       return redirect('jurnal')->with('message', 'Tutup Buku Berhasil');
    }

    public function queryTutupBuku($warehouse_id,$tahun,$jenis)
    {
        $query = TbRekeningSaldo::selectRaw('tb_rekening_saldos.*,tb_rekenings.nama,tb_rekenings.kategori,tb_rekenings.jenis_mutasi')
                                ->leftJoin('tb_rekenings','tb_rekening_saldos.tb_rekening_kode','=','tb_rekenings.kode')
                                ->where('kategori',$jenis)
                                ->where('tb_rekening_saldos.warehouse_id',$warehouse_id)
                                ->where('tb_rekening_saldos.tahun',$tahun)
                                ->get()
                                ->toArray();
        
        /*$saldo = 0;
        foreach ($query as $key => $value) {
            $t_debit = 0; $t_kredit = 0;
            for ($i=1; $i < 13; $i++) { 
                $x = $i < 10 ? '0'.$i : $i; 

                $t_debit  += $value['debit_'.$x];
                $t_kredit += $value['kredit_'.$x];
            }

            $t_saldo = $value['jenis_mutasi'] == 'debit' ? $t_debit - $t_kredit : $t_kredit - $t_debit;
            $saldo += $t_saldo;
        }

        return $saldo;*/

        $ret = array();
        foreach ($query as $key => $value) {
            $t_debit = 0; $t_kredit = 0;
            for ($i=1; $i < 13; $i++) { 
                $x = $i < 10 ? '0'.$i : $i; 

                $t_debit  += $value['debit_'.$x];
                $t_kredit += $value['kredit_'.$x];
            }

            $t_saldo = $value['jenis_mutasi'] == 'debit' ? $t_debit - $t_kredit : $t_kredit - $t_debit;
            
            if($t_saldo !=0){
                 $ret[] = ['kode'=>$value['tb_rekening_kode'],
                        'akun'=>$value['nama'],
                        'jenis_mutasi'=>$value['jenis_mutasi'],
                        'saldo'=>$t_saldo]; 
            }
             
        }

        return $ret;
    }
}
