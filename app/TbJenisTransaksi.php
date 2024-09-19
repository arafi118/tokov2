<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Warehouse;
use App\TbJurnalDetail;

class TbJenisTransaksi extends Model
{
    use HasFactory;

    use UsesTenantConnection;
    
    protected $fillable = ['tb_induk_jenis_transaksi_id','nama','slug','deskripsi'];

    public function queryInventaris($bulan,$tahun,$hari,$warehouse_id,$inventaris_id)
    {
        $warehouse = Warehouse::findOrFail($warehouse_id);
        $query   = TbJurnalDetail::selectRaw('tb_jurnals.tgl_transaksi,
                                             tb_jurnals.nomor_transaksi,
                                             users.name,
                                             users.fullname,
                                             tb_jurnal_details.debit_kode,
                                             tb_jurnal_details.kredit_kode,
                                             tb_jurnal_details.debit_nominal,
                                             tb_jurnal_details.kredit_nominal,
                                             tb_jurnal_details.deskripsi,
                                             inventaris.id,
                                             inventaris.jenis,
                                             inventaris.relasi,
                                             inventaris.harga_satuan,
                                             inventaris.unit,
                                             inventaris.nama_barang,
                                             inventaris.umur_ekonomis,
                                             inventaris.tgl_beli,
                                             inventaris.tgl_validasi,
                                             inventaris.status
                                ')
                                ->leftJoin('tb_jurnals','tb_jurnals.id','=','tb_jurnal_details.tb_jurnal_id')
                                ->leftJoin('inventaris','inventaris.tb_jurnal_id','=','tb_jurnals.id')
                                ->leftJoin('users','users.id','=','tb_jurnals.insertedby');

       $tgl    = $tahun . '-' . $bulan . '-' . $hari;
       $w_name = $warehouse->name;

       if (strlen($hari) > 0 && strlen($bulan) > 0) {
            
            $data['transaksi'] = $query->where('tgl_transaksi', $tgl);
       } elseif (strlen($bulan) > 0) {
            
            $data['transaksi'] = $query->whereMonth('tgl_transaksi',$bulan)
                                       ->whereYear('tgl_transaksi',$tahun);
       } else {
           
            $data['transaksi'] = $query->whereYear('tgl_transaksi',$tahun);
       }
 
       if($warehouse_id !=null){
            $query->where('tb_jurnals.warehouse_id',$warehouse_id);
       }

       if($inventaris_id !=null){
            $query->where('tb_jurnal_details.tb_jenis_transaksi_id',$inventaris_id);
       }

       $data['transaksi'] =  $query->where('tb_jurnals.tb_induk_jenis_transaksi_id',9)->get();

       return $data['transaksi'];
    }
}
