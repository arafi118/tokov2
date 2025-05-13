<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TbJurnal;
use App\TbJurnalDetail;
use App\TbJenisTransaksi;
use App\TbRekening;
use App\TbIndukJenisTransaksi;
use App\Warehouse;
use App\Inventaris;
use Carbon\Carbon;

class JurnalController extends Controller
{
    public function __construct()
    {
        $this->jurnal = new TbJurnal;
    }

    public function index(Request $req)
    {
        $jurnal = TbJurnal::selectRaw('tb_jurnals.*,tb_induk_jenis_transaksis.nama as induk')
            ->leftJoin('tb_induk_jenis_transaksis', 'tb_induk_jenis_transaksis.id', '=', 'tb_jurnals.tb_induk_jenis_transaksi_id')
            ->orderBy('tgl_transaksi', 'desc')->get();

        if ($req->cetak == 'yes' || $req->xls == 'yes') {
            $data = TbJurnal::with('induk')->get();

            return view('backend.jurnal.print', compact('data'));
        }

        return view('backend.jurnal.index', compact('jurnal'));
    }

    public function create()
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $induk               = TbIndukJenisTransaksi::where('slug', 'LIKE', 'input-jurnal')->with([
            'jenisTransaksi' => function ($query) {
                $query->where('nama', 'LIKE', '%Aset%');
            }
        ])->first();
        $lims_account_all    = TbRekening::where('depth', '3')->orderBy('kode', 'asc')->get();

        return view('backend.jurnal.form', compact('lims_warehouse_list', 'induk', 'lims_account_all'));
    }

    public function edit($id)
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $induk               = TbIndukJenisTransaksi::where('slug', 'LIKE', 'input-jurnal')->with([
            'jenisTransaksi' => function ($query) {
                $query->where('nama', 'LIKE', '%Aset%');
            }
        ])->first();
        $lims_account_all    = TbRekening::where('depth', '3')->orderBy('kode', 'asc')->get();
        $jurnal              = TbJurnal::find($id);

        $inventaris = null;

        if ($jurnal->tb_induk_jenis_transaksi_id == '9') {
            $inventaris = Inventaris::where('tb_jurnal_id', $id)->first();
        }

        $detail             = TbJurnalDetail::where('tb_jurnal_id', $jurnal->id)->first();

        return view('backend.jurnal.form', compact('lims_warehouse_list', 'induk', 'lims_account_all', 'jurnal', 'detail', 'inventaris'));
    }

    public function store(Request $req)
    {
        \DB::beginTransaction();

        try {
            if ($req->jurnal_id != '') {
                $jurnal = TbJurnal::find($req->jurnal_id);
            } else {
                $jurnal = new TbJurnal;
            }

            $jenisTransaksi = TbJenisTransaksi::where('id', $req->jenis_transaksi)->first();
            $no_trans = $req->nomor_transaksi != '' ? $req->nomor_transaksi : $this->jurnal->notaCounter('input_jurnal');

            $jurnal->warehouse_id = $req->warehouse_id;
            $jurnal->tb_induk_jenis_transaksi_id = $jenisTransaksi->tb_induk_jenis_transaksi_id;
            $jurnal->tgl_transaksi  = Carbon::parse($req->tgl_transaksi)->format('Y-m-d');
            $jurnal->tabel_transaksi = 'jurnals';
            $jurnal->nomor_transaksi  = $no_trans;
            $jurnal->memo  = $req->Keterangan;
            $jurnal->insertedby = auth()->user()->id;
            $jurnal->save();

            if ($req->jurnal_detail_id != 0) {
                $detail = TbJurnalDetail::findOrFail($req->jurnal_detail_id);
            } else {
                $detail = new TbJurnalDetail;
            }

            $deskripsi = $req->Keterangan;
            if ($req->induk_transaksi == 9) {
                $deskripsi = 'Pembelian AT dan Inventaris (' . $req->nama_barang . ')';
            }

            $jenis_transaksi_id = $jenisTransaksi->id;
            $detail->tb_jurnal_id = $jurnal->id;
            $detail->tb_jenis_transaksi_id = $jenis_transaksi_id;
            $detail->debit_kode = $req->disimpan_ke;
            $detail->kredit_kode = $req->sumber_dana;
            $detail->debit_nominal = str_replace(',', '', $req->nominal);
            $detail->kredit_nominal = str_replace(',', '', $req->nominal);
            $detail->deskripsi = $deskripsi;
            $detail->relasi = $req->relasi;
            $detail->save();

            $params  = [
                'debit_kode'         => $req->disimpan_ke,
                'debit_jenis_mutasi' => '',
                'kredit_kode'        => $req->sumber_dana,
                'kredit_jenis_mutasi' => '',
                'gudang'             => $req->warehouse_id,
                'tanggal_transaksi'  => Carbon::parse($req->tgl_transaksi)->format('Y-m-d')
            ];

            $this->jurnal->updateSaldo($params);
            $this->jurnal->updateLabaRugi($params);

            if ($req->induk_transaksi == 9) {
                $jenis = TbJenisTransaksi::find($req->jenis_transaksi);

                if ($req->jurnal_id != '') {
                    $inventaris = Inventaris::where('tb_jurnal_id', $req->jurnal_id)->first();
                } else {
                    $inventaris = new Inventaris;
                }

                $inventaris->tb_jurnal_id  = $req->jurnal_id != '' ? $req->jurnal_id : $jurnal->id;
                $inventaris->tgl_beli      = Carbon::parse($req->tgl_transaksi)->format('Y-m-d');
                $inventaris->jenis         = $jenis->nama;
                $inventaris->relasi        = $req->relasi;
                $inventaris->nama_barang   = $req->nama_barang;
                $inventaris->umur_ekonomis = $req->umur_ekonomis;
                $inventaris->unit          = $req->unit;
                $inventaris->harga_satuan  = $req->harga_satuan;
                $inventaris->status        = $req->status;
                $inventaris->tgl_validasi  = Carbon::parse($req->tgl_transaksi)->format('Y-m-d');
                $inventaris->save();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error("jurnal store : {$e->getMessage()}");


            $response = false;
            return $e->getMessage();
        }

        \DB::commit();

        return redirect('jurnal')->with('message', 'Jurnal diperbarui');
    }

    public function destroy($id)
    {
        $jurnal = TbJurnal::find($id);
        $this->jurnal->hapus_by_ref($jurnal->nomor_transaksi);
        $jurnal->delete();

        return redirect('jurnal')->with('message', 'Jurnal dihapus');
    }

    public function detail($id)
    {
        $jurnal  = TbJurnal::selectRaw('tb_jurnals.*,tb_induk_jenis_transaksis.nama as induk,warehouses.name as warehouse')
            ->leftJoin('warehouses', 'warehouses.id', '=', 'tb_jurnals.warehouse_id')
            ->leftJoin('tb_induk_jenis_transaksis', 'tb_induk_jenis_transaksis.id', '=', 'tb_jurnals.tb_induk_jenis_transaksi_id')
            ->where('tb_jurnals.id', $id)
            ->first();

        $details = TbJurnalDetail::selectRaw('tb_jurnal_details.*,
                                              debit.nama as debit_nama,kredit.nama as kredit_nama,
                                              tb_jenis_transaksis.nama as jenis_transaksi
                                              ')
            ->leftJoin('tb_jenis_transaksis', 'tb_jenis_transaksis.id', '=', 'tb_jurnal_details.tb_jenis_transaksi_id')
            ->leftJoin('tb_rekenings as debit', 'debit.kode', '=', 'tb_jurnal_details.debit_kode')
            ->leftJoin('tb_rekenings as kredit', 'kredit.kode', '=', 'tb_jurnal_details.kredit_kode')
            ->where('tb_jurnal_details.tb_jurnal_id', $jurnal->id)
            ->get();

        return view('backend.jurnal.detail', compact('jurnal', 'details'));
    }

    public function getJenisTransaksi(Request $request)
    {
        $data = TbJenisTransaksi::selectRaw('tb_jenis_transaksis.id,tb_jenis_transaksis.nama,
                                             tb_konfigurasi_transaksis.rekening_debit_kode,
                                             tb_konfigurasi_transaksis.rekening_kredit_kode
                                             ')
            ->leftJoin('tb_konfigurasi_transaksis', 'tb_konfigurasi_transaksis.tb_jenis_transaksi_id', '=', 'tb_jenis_transaksis.id')
            ->where('tb_induk_jenis_transaksi_id', $request->tb_induk_jenis_transaksi_id)
            ->get();

        return response()->json(compact('data'), 200);
    }
}
