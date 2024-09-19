<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use App\TbJurnal;
use App\TbJurnalDetail;
use App\TbRekening;
use App\TbRekeningSaldo;
use App\TbJenisTransaksi;
use Carbon\Carbon;
use App\Exports\JurnalExport;
use App\Exports\BukuBesarExport;
use App\Exports\InventarisExport;
use App\Exports\NeracaExport;
use App\Exports\RugiLabaExport;
use App\Exports\NeracaSaldoExport;
use Excel;
use PDF;

class LaporanKeuanganController extends Controller
{
    public function __construct()
    {
        $this->jurnal = new TbJurnal;
        $this->jenis_laporan = ['jurnal' => 'Jurnal', 'buku_besar' => 'Buku Besar', 'neraca_saldo' => 'Neraca Saldo', 'neraca' => 'Neraca Percobaan', 'rugi_laba' => 'Laba Rugi', 'arus_kas' => 'Arus Kas', 'inventaris' => 'Inventaris'];
    }

    public function index(Request $req)
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $jenis_buku    = TbRekening::where('depth', 3)->orderBy('kode', 'ASC')->get();
        $jenis_laporan = $this->jenis_laporan;
        $inventaris = TbJenisTransaksi::where('tb_induk_jenis_transaksi_id', 9)->get();

        $params = compact('lims_warehouse_list', 'jenis_buku', 'jenis_laporan', 'inventaris');

        return view('backend.laporan_keuangan.index', $params);
    }

    public function jurnal(Request $req)
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $tahun = $req->input('tahun');
        $bulan = $req->input('bulan');
        $hari  = $req->input('hari');
        $warehouse_id = $req->input('warehouse_id');
        $warehouse    = Warehouse::find($warehouse_id);
        $w_name       = $warehouse != null ? $warehouse->name : '';

        $tgl   = $tahun . '-' . $bulan . '-' . $hari;
        $query = TbJurnal::with([
            'details',
            'details.rek_debit',
            'details.rek_kredit',
            'induk',
            'pic',
        ]);
        if (strlen($hari) > 0 && strlen($bulan) > 0) {
            $data['sub_judul'] = 'Tanggal ' . $hari . ' Bulan ' . bulan($bulan) . ' ' . $tahun . ' ' . $w_name;
            $data['transaksi'] = $query->where('tgl_transaksi', $tgl);
        } elseif (strlen($bulan) > 0) {
            $data['sub_judul'] = 'Bulan ' . bulan($bulan) . ' ' . $tahun . ' ' . $w_name;
            $data['transaksi'] = $query->whereMonth('tgl_transaksi', $bulan)
                ->whereYear('tgl_transaksi', $tahun);
        } else {
            $data['sub_judul'] = 'Tahun ' . $tahun . ' ' . $w_name;
            $data['transaksi'] = $query->whereYear('tgl_transaksi', $tahun);
        }

        if ($warehouse_id != null) {
            $data['transaksi'] = $data['transaksi']->where('warehouse_id', $warehouse_id);
        }

        $data['transaksi'] = $data['transaksi']->orderBy('tgl_transaksi', 'ASC')->get();

        $params = compact('lims_warehouse_list', 'data', 'tahun', 'bulan', 'hari', 'warehouse_id');

        if ($req->print == 'yes') {

            $params += ['header' => 'yes', 'logo' => 'yes'];

            $pdf = PDF::loadview('backend.laporan_keuangan.jurnal.print', $params, [], ['orientation' => 'P', 'format' => 'A4']);

            return $pdf->stream('laporan-jurnal.pdf');
        }

        if ($req->xls == 'yes') {

            $params += ['header' => 'no'];

            return Excel::download(new JurnalExport($params), 'jurnal.xlsx');
        }

        return view('backend.laporan_keuangan.jurnal.index', $params);
    }

    public function inventaris(Request $req)
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $tahun = $req->input('tahun');
        $bulan = $req->input('bulan');
        $hari  = $req->input('hari');
        $warehouse_id  = $req->input('warehouse_id');
        $warehouse     = Warehouse::find($warehouse_id);
        $w_name        = $warehouse != null ? $warehouse->name : '';
        $inventaris_id = $req->input('inventaris_id');
        $q_inventaris = TbJenisTransaksi::where('tb_induk_jenis_transaksi_id', 9);

        if ($inventaris_id != '') {
            $q_inventaris->where('id', $inventaris_id);
        }

        $inventaris = $q_inventaris->get();

        if (strlen($hari) > 0 && strlen($bulan) > 0) {
            $data['sub_judul'] = 'Tanggal ' . $hari . ' Bulan ' . bulan($bulan) . ' ' . $tahun . ' ' . $w_name;
        } elseif (strlen($bulan) > 0) {
            $data['sub_judul'] = 'Bulan ' . bulan($bulan) . ' ' . $tahun . ' ' . $w_name;
        } else {
            $data['sub_judul'] = 'Tahun ' . $tahun . ' ' . $w_name;
        }

        $params = compact('lims_warehouse_list', 'data', 'tahun', 'bulan', 'hari', 'warehouse_id', 'inventaris', 'inventaris_id');

        if ($req->print == 'yes') {

            $params += ['header' => 'yes', 'logo' => 'yes'];

            $pdf = PDF::loadview('backend.laporan_keuangan.inventaris.print', $params, [], ['orientation' => 'L', 'format' => 'A4']);

            return $pdf->stream('laporan-inventaris.pdf');
        }

        if ($req->xls == 'yes') {

            $params += ['header' => 'no'];

            return Excel::download(new InventarisExport($params), 'inventaris.xlsx');
        }
    }

    public function bukuBesar(Request $req)
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $tahun = $req->input('tahun');
        $bulan = $req->input('bulan');
        $hari  = $req->input('hari');
        $warehouse_id  = $req->input('warehouse_id');
        $warehouse     = Warehouse::find($warehouse_id);
        $w_name        = $warehouse != null ? $warehouse->name : '';
        $jenis_buku_id = $req->input('jenis_buku_id');
        $jenis_buku    = TbRekening::where('depth', 3)->orderBy('kode', 'ASC')->get();
        $rekening_info = TbRekening::where('kode', $jenis_buku_id)->first();

        $data['transaksi'] = '';
        if ($jenis_buku_id != '') {
            $tahun_lalu = $tahun - 1;
            $xbulan     = $bulan - 1;
            $bulan_lalu = strlen($xbulan) > 1 ? $xbulan : '0' . $xbulan;

            $rekening_saldo = new TbRekeningSaldo;
            $data['saldo_tahun_lalu'] = $rekening_saldo->getSaldoAwalTahunLalu($jenis_buku_id, $tahun_lalu, $warehouse_id);
            $data['saldo_bulan_lalu'] = $rekening_saldo->getSaldoBulanLalu($jenis_buku_id, $tahun, $bulan_lalu, $warehouse_id);

            $query   = TbJurnalDetail::selectRaw('tb_jurnals.id,
                                                tb_jurnals.tgl_transaksi,
                                                tb_jurnals.nomor_transaksi,
                                                users.name,
                                                users.fullname,
                                                users.initial,
                                                tb_jurnal_details.debit_kode,
                                                tb_jurnal_details.kredit_kode,
                                                tb_jurnal_details.debit_nominal,
                                                tb_jurnal_details.kredit_nominal,
                                                tb_jurnal_details.deskripsi')
                ->leftJoin('tb_jurnals', 'tb_jurnals.id', '=', 'tb_jurnal_details.tb_jurnal_id')
                ->leftJoin('users', 'users.id', '=', 'tb_jurnals.insertedby');

            $tgl = $tahun . '-' . $bulan . '-' . $hari;
            if (strlen($hari) > 0 && strlen($bulan) > 0) {
                $data['sub_judul'] = 'Tanggal ' . $hari . ' Bulan ' . bulan($bulan) . ' ' . $tahun . ' ' . $w_name;
                $data['transaksi'] = $query->where('tgl_transaksi', $tgl);
            } elseif (strlen($bulan) > 0) {
                $data['sub_judul'] = 'Bulan ' . bulan($bulan) . ' ' . $tahun . ' ' . $w_name;
                $data['transaksi'] = $query->whereMonth('tgl_transaksi', $bulan)
                    ->whereYear('tgl_transaksi', $tahun);
            } else {
                $data['sub_judul'] = 'Tahun ' . $tahun . ' ' . $w_name;
                $data['transaksi'] = $query->whereYear('tgl_transaksi', $tahun);
            }

            if ($warehouse_id != null) {
                $data['transaksi'] = $data['transaksi']->where('tb_jurnals.warehouse_id', $warehouse_id);
            }

            $data['transaksi'] =  $data['transaksi']->where(function ($q) use ($jenis_buku_id) {
                $q->where('debit_kode', $jenis_buku_id)
                    ->orWhere('kredit_kode', $jenis_buku_id);
            })->orderBy('tgl_transaksi', 'ASC')->orderBy('id', 'ASC')->get();
        }

        $params = compact('lims_warehouse_list', 'data', 'tahun', 'bulan', 'hari', 'warehouse_id', 'jenis_buku', 'jenis_buku_id', 'rekening_info');

        if ($req->print == 'yes') {

            $params += ['header' => 'yes', 'logo' => 'yes'];

            $pdf = PDF::loadview('backend.laporan_keuangan.buku_besar.print', $params, [], ['orientation' => 'P', 'format' => 'A4']);

            return $pdf->stream('laporan-buku-besar.pdf');
        }

        if ($req->xls == 'yes') {

            $params += ['header' => 'no'];

            return Excel::download(new BukuBesarExport($params), 'buku-besar.xlsx');
        }

        return view('backend.laporan_keuangan.buku_besar.index', $params);
    }

    public function neraca(Request $req)
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $tahun = $req->input('tahun');
        $bulan = $req->input('bulan');
        $hari  = $req->input('hari');
        $warehouse_id = $req->input('warehouse_id');
        $warehouse    = Warehouse::find($warehouse_id);
        $w_name       = $warehouse != null ? $warehouse->name : '';

        $data   = array();
        $params = compact('lims_warehouse_list', 'tahun', 'bulan', 'hari', 'warehouse_id');
        if ($bulan != null && $tahun != null) {
            $k_debit   = 'debit_' . $bulan;
            $k_kredit  = 'kredit_' . $bulan;

            $data['aset']      = $this->queryNeraca($k_debit, $k_kredit, 1, $tahun, $warehouse_id);

            $data['utang']     = $this->queryNeraca($k_debit, $k_kredit, 2, $tahun, $warehouse_id);
            $data['modal']     = $this->queryNeraca($k_debit, $k_kredit, 3, $tahun, $warehouse_id);
            $data['sub_judul'] = 'Bulan ' . bulan($bulan) . ' ' . $tahun . ' ' . $w_name;

            $params += ['data' => $data];

            if ($req->print == 'yes') {

                $params += ['header' => 'yes', 'logo' => 'yes'];

                $pdf = PDF::loadview('backend.laporan_keuangan.neraca.print', $params, [], ['orientation' => 'P', 'format' => 'A4']);

                return $pdf->stream('laporan-neraca.pdf');
            }

            if ($req->xls == 'yes') {

                $params += ['header' => 'no'];

                return Excel::download(new NeracaExport($params), 'neraca.xlsx');
            }
        }

        return view('backend.laporan_keuangan.neraca.index', $params);
    }

    private function queryNeraca($k_debit, $k_kredit, $base_kode, $tahun, $warehouse_id)
    {
        $stat = \DB::connection(env('TENANT_DB_CONNECTION'))->statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        $query = \DB::connection(env('TENANT_DB_CONNECTION'))
            ->select(\DB::raw("SELECT tb_rekenings.id,
                                       tb_rekenings.kode as parent_code,
                                       tb_rekenings.nama as parent,
                                       x.kode as child_code,
                                       x.nama as child,
                                       COALESCE(x.dsum,0) as debit,
                                       COALESCE(x.ksum,0) as kredit,
                                       tb_rekenings.depth,
                                       tb_rekenings.jenis_mutasi
                                FROM tb_rekenings
                                LEFT JOIN(
                                    SELECT parent_id,
                                           kode,nama,
                                           SUM($k_debit) AS dsum,
                                           SUM($k_kredit) AS ksum
                                    FROM tb_rekenings
                                    LEFT JOIN tb_rekening_saldos ON tb_rekening_saldos.tb_rekening_kode  = tb_rekenings.kode
                                    WHERE depth = 3
                                    AND tahun = $tahun
                                    AND warehouse_id = $warehouse_id
                                    GROUP BY parent_id
                                ) AS x ON x.parent_id = id
                                WHERE depth IN (0,1,2)
                                AND tb_rekenings.kode LIKE '$base_kode%' 
                                ORDER BY tb_rekenings.kode ASC
                                "));

        return $query;
    }

    public function rugiLaba(Request $req)
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $tahun = $req->input('tahun');
        $bulan = $req->input('bulan');
        $hari  = $req->input('hari');
        $warehouse_id = $req->input('warehouse_id');
        $warehouse    = Warehouse::find($warehouse_id);
        $w_name       = $warehouse != null ? $warehouse->name : '';

        $data = array();
        $params = compact('lims_warehouse_list', 'tahun', 'bulan', 'hari', 'warehouse_id');
        if ($bulan != null && $tahun != null) {
            $k_debit    = 'debit_' . $bulan;
            $k_kredit   = 'kredit_' . $bulan;
            $tahun_lalu = $tahun - 1;
            $xbulan     = $bulan - 1;
            $bulan_lalu = str_pad($xbulan, 2, '0', STR_PAD_LEFT);
            // $args     = [
            //     'gudang' => $warehouse_id,
            //     'tanggal_transaksi' => $tahun . '-' . $bulan
            // ];

            // $data = $this->jurnal->updateLabaRugi($args, 'yes');

            $rekening = TbRekening::where('kode', 'LIKE', '1.1.03.01')->orwhere(
                function ($query) {
                    $query->where('depth', '3')->where(function ($query) {
                        $query->where('kode', 'LIKE', '4.%')
                            ->orwhere('kode', 'LIKE', '5.%')
                            ->orwhere('kode', 'LIKE', '6.%')
                            ->orwhere('kode', 'LIKE', '7.%');
                    });
                }
            )->with([
                'komsaldo' => function ($query) use ($tahun, $warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id)->whereIn('tahun', [$tahun - 1, $tahun]);
                },
                'komsaldo.trx_debit' => function ($query) use ($bulan, $tahun) {
                    $query->selectRaw('SUM(debit_nominal) as s_debit,tb_jurnals.tgl_transaksi,debit_nominal,debit_kode,tb_jenis_transaksis.slug')
                        ->leftJoin('tb_jurnals', 'tb_jurnals.id', '=', 'tb_jurnal_details.tb_jurnal_id')
                        ->leftJoin('tb_jenis_transaksis', 'tb_jenis_transaksis.id', '=', 'tb_jurnal_details.tb_jenis_transaksi_id')
                        ->whereMonth('tgl_transaksi', $bulan)
                        ->whereYear('tgl_transaksi', $tahun)
                        ->whereIn('slug', ['pembelian-tunai', 'pembelian-transfer', 'pembelian-tempo', 'validasi-penerimaan-barang-po-tunai', 'validasi-penerimaan-barang-po-transfer'])
                        ->first();
                },
                'komsaldo.trx_kredit' => function ($query) use ($bulan, $tahun) {
                    $query->selectRaw('SUM(debit_nominal) as s_debit,tb_jurnals.tgl_transaksi,debit_nominal,debit_kode,tb_jenis_transaksis.slug')
                        ->leftJoin('tb_jurnals', 'tb_jurnals.id', '=', 'tb_jurnal_details.tb_jurnal_id')
                        ->leftJoin('tb_jenis_transaksis', 'tb_jenis_transaksis.id', '=', 'tb_jurnal_details.tb_jenis_transaksi_id')
                        ->whereMonth('tgl_transaksi', $bulan)
                        ->whereYear('tgl_transaksi', $tahun)
                        ->whereIn('slug', ['pembelian-tunai', 'pembelian-transfer', 'pembelian-tempo', 'validasi-penerimaan-barang-po-tunai', 'validasi-penerimaan-barang-po-transfer'])
                        ->first();
                }
            ])->get();

            $group = [
                '1' => [
                    'nama' => 'Laba Kotor',
                    'total' => 'Total Pembelian'
                ],
                '2' => [
                    'nama' => 'Pendapatan Lain Lain',
                    'total' => ''
                ],
                '3' => [
                    'nama' => 'Beban Operasional',
                    'total' => 'Jumlah Beban Operasional'
                ],
                '4' => [
                    'nama' => 'Pendapatan Non Usaha',
                    'total' => 'Jumlah Pendapatan Non Usaha'
                ],
                '5' => [
                    'nama' => 'Beban Non Usaha',
                    'total' => 'Jumlah Beban Non Usaha'
                ],
                '6' => [
                    'nama' => 'Beban Pajak',
                    'total' => 'Jumlah Beban Pajak'
                ],
            ];

            foreach ($rekening as $rek) {
                $debit_bulan_ini = 'debit_' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
                $kredit_bulan_ini = 'kredit_' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
                $debit_bulan_lalu = 'debit_' . $bulan_lalu;
                $kredit_bulan_lalu = 'kredit_' . $bulan_lalu;
                $debit_tahun_lalu = 'debit_12';
                $kredit_tahun_lalu = 'kredit_12';

                $saldo = 0;
                $saldo_bulan_ini = 0;
                $saldo_bulan_lalu = 0;
                $saldo_tahun_lalu = 0;
                if ($rek->kode == '1.1.03.01') {
                    foreach ($rek->komsaldo as $saldo) {
                        if ($saldo->tahun == $tahun) {
                            $saldo_bulan_ini = $saldo->trx_debit->s_debit;
                            $komsaldo = $saldo->$debit_bulan_ini - $saldo->$kredit_bulan_ini;
                            $saldo_bulan_lalu = $saldo->$debit_bulan_lalu - $saldo->$kredit_bulan_lalu;
                        } else {
                            $saldo_tahun_lalu = $saldo->$debit_tahun_lalu - $saldo->$kredit_tahun_lalu;
                        }
                    }

                    $group[$rek->kode] = [
                        'kode' => $rek->kode,
                        'nama' => $rek->nama,
                        'saldo_bulan_ini' => $saldo_bulan_ini,
                        'saldo_bulan_lalu' => $saldo_bulan_lalu,
                        'saldo_tahun_lalu' => $saldo_tahun_lalu,
                        'saldo' => $komsaldo
                    ];
                } else {
                    $kode = $rek->kode;
                    $kode1 = explode('.', $rek->kode)[0];
                    $kode2 = explode('.', $rek->kode)[1];
                    $kode3 = explode('.', $rek->kode)[2];
                    $kode4 = explode('.', $rek->kode)[3];

                    $debit = 0;
                    $kredit = 0;
                    $debit_bl = 0;
                    $kredit_bl = 0;
                    $debit_tl = 0;
                    $kredit_tl = 0;
                    foreach ($rek->komsaldo as $saldo) {
                        if ($saldo->tahun == $tahun) {
                            $debit = $saldo->$debit_bulan_ini;
                            $kredit = $saldo->$kredit_bulan_ini;

                            $debit_bl = $saldo->$debit_bulan_lalu;
                            $kredit_bl = $saldo->$kredit_bulan_lalu;
                        } else {
                            $debit_tl = $saldo->$debit_tahun_lalu;
                            $kredit_tl = $saldo->$kredit_tahun_lalu;
                        }
                    }

                    if ($rek->jenis_mutasi == 'kredit') {
                        $saldo_bulan_ini = $kredit - $debit;
                        $saldo_bulan_lalu = $kredit_bl - $debit_bl;
                        $saldo_tahun_lalu = $kredit_tl - $debit_tl;
                    }

                    $saldo = [
                        'kode' => $rek->kode,
                        'nama' => $rek->nama,
                        'saldo_bulan_ini' => $saldo_bulan_ini,
                        'saldo_bulan_lalu' => $saldo_bulan_lalu,
                        'saldo_tahun_lalu' => $saldo_tahun_lalu,
                    ];

                    if ($kode1 <= '5' && $kode != '4.1.01.05') {
                        if ($kode == '4.1.01.04' || $kode == '5.1.01.01') {
                            continue;
                        }

                        if ($kode == '5.1.01.02') {
                            $group['1']['kode'][] = $group['1.1.03.01'];
                            unset($group['1.1.03.01']);
                        }

                        $group['1']['kode'][] = $saldo;
                    }

                    if ($kode1 == '6') {
                        $group['3']['kode'][] = $saldo;
                    }

                    if ($kode1 == '7' && $kode2 <= '2') {
                        $group['4']['kode'][] = $saldo;
                    }

                    if ($kode1 == '7' && $kode2 == '3') {
                        $group['5']['kode'][] = $saldo;
                    }

                    if ($kode1 == '7' && $kode2 == '4') {
                        $group['6']['kode'][] = $saldo;
                    }

                    if ($kode == '4.1.01.05') {
                        $group['2']['kode'][] = $saldo;
                    }
                }
            }

            $data['laba_kotor']['penjualan']       = $this->jurnal->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '4.1.01.01');
            $data['laba_kotor']['pot_penjualan']       = $this->jurnal->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '4.1.01.02');
            $data['laba_kotor']['retur_penjualan']       = $this->jurnal->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '4.1.01.03');

            $data['laba_kotor']['persediaan_awal'] = $this->jurnal->queryPersediaanAwal($bulan_lalu, $tahun_lalu, $tahun, $warehouse_id);
            $data['laba_kotor']['persediaan'] = $this->jurnal->queryPersediaanPembelian($bulan, $tahun, $warehouse_id);
            $data['laba_kotor']['retur_pembelian'] = $this->jurnal->queryReturPembelian($bulan, $tahun, $warehouse_id);

            $data['laba_kotor']['beban_pengolahan']       = $this->jurnal->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '5.1.01.04');
            $data['laba_kotor']['beban_angkut']       = $this->jurnal->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '5.1.01.05');
            $data['laba_kotor']['pot_pembelian']       = $this->jurnal->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '5.1.01.02');
            $data['laba_kotor']['persediaan_akhir']       = $this->jurnal->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '1.1.03.01');
            $data['pendapatan_lain_lain']       = $this->jurnal->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '4.1.01.05');
            $data['beban_operasional'] = $this->jurnal->queryRugiLaba($k_debit, $k_kredit, '6.1', $tahun, $warehouse_id);
            $pendapatan_non_usaha      = $this->jurnal->queryRugiLaba($k_debit, $k_kredit, '7.1', $tahun, $warehouse_id);
            $pendapatan_non_usaha_2    = $this->jurnal->queryRugiLaba($k_debit, $k_kredit, '7.2', $tahun, $warehouse_id);
            $data['pendapatan_non_usaha']   = array_merge($pendapatan_non_usaha, $pendapatan_non_usaha_2);
            $data['beban_non_usaha']   = $this->jurnal->queryRugiLaba($k_debit, $k_kredit, '7.3', $tahun, $warehouse_id);
            $data['beban_pajak']       = $this->jurnal->queryRugiLaba($k_debit, $k_kredit, '7.4', $tahun, $warehouse_id);

            $data['rekening'] = $group;

            $data['sub_judul']                 = 'Bulan ' . bulan($bulan) . ' ' . $tahun . ' ' . $w_name;
            $params += ['data' => $data];

            if ($req->print == 'yes') {

                $params += ['header' => 'yes', 'logo' => 'yes'];

                $pdf = PDF::loadview('backend.laporan_keuangan.rugi_laba.print', $params, [], ['orientation' => 'P', 'format' => 'A4']);

                return $pdf->stream('laporan-rugi-laba.pdf');
            }

            if ($req->xls == 'yes') {

                $params += ['header' => 'no'];

                return Excel::download(new RugiLabaExport($params), 'rugi-laba.xlsx');
            }
        }

        return view('backend.laporan_keuangan.rugi_laba.index', $params);
    }

    public function arusKas(Request $req)
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $tahun = $req->input('tahun');
        $bulan = $req->input('bulan');
        $hari  = $req->input('hari');
        $warehouse_id = $req->input('warehouse_id');
        $warehouse    = Warehouse::find($warehouse_id);
        $w_name       = $warehouse != null ? $warehouse->name : '';

        $data = array();
        // $params = array();
        $params = compact('lims_warehouse_list', 'data', 'tahun', 'bulan', 'hari', 'warehouse_id');

        return view('backend.laporan_keuangan.arus_kas.index', $params);
    }

    public function neracaSaldo(Request $req)
    {
        $arr_laba_rugi = [
            '4.1.01.01',
            '4.1.01.02',
            '4.1.01.03',
            '1.1.03.01',
            '5.1.01.04',
            '5.1.01.05',
            '5.1.01.02',
            '5.1.01.03',
            '4.1.01.05',
            '6.1.01.01',
            '6.1.02.01',
            '6.1.03.01',
            '6.1.04.01',
            '6.1.05.01',
            '6.1.06.01',
            '6.1.07.01',
            '6.1.08.01',
            '6.1.08.02',
            '6.1.08.03',
            '6.1.08.04',
            '6.1.08.05',
            '6.1.08.06',
            '6.1.09.01',
            '6.1.09.01',
            '6.1.10.01',
            '7.1.01.01',
            '7.1.01.02',
            '7.2.01.01',
            '7.3.01.01',
            '7.3.01.02',
            '7.3.02.01',
            '7.4.01.01',
            '7.4.01.02'
        ];
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $tahun = $req->input('tahun');
        $bulan = $req->input('bulan');
        $hari  = $req->input('hari');
        $warehouse_id = $req->input('warehouse_id');
        $warehouse    = Warehouse::find($warehouse_id);
        $w_name       = $warehouse != null ? $warehouse->name : '';

        $data   = array();
        $params = compact('lims_warehouse_list', 'tahun', 'bulan', 'hari', 'warehouse_id', 'arr_laba_rugi');
        if ($bulan != null && $tahun != null) {
            $k_debit   = 'debit_' . $bulan;
            $k_kredit  = 'kredit_' . $bulan;

            $data['neraca_saldo'] = $this->queryNeracaSaldo($k_debit, $k_kredit, $tahun, $warehouse_id);

            $data['sub_judul']    = 'Bulan ' . bulan($bulan) . ' ' . $tahun . ' ' . $w_name;

            $params += ['data' => $data, 'k_debit' => $k_debit, 'k_kredit' => $k_kredit];

            if ($req->print == 'yes') {

                $params += ['header' => 'yes', 'logo' => 'yes'];

                $pdf = PDF::loadview('backend.laporan_keuangan.neraca_saldo.print', $params, [], ['orientation' => 'P', 'format' => 'A4']);

                return $pdf->stream('laporan-neraca-saldo.pdf');
            }

            if ($req->xls == 'yes') {

                $params += ['header' => 'no'];

                return Excel::download(new NeracaSaldoExport($params), 'neraca-saldo.xlsx');
            }
        }

        return view('backend.laporan_keuangan.neraca_saldo.index', $params);
    }

    public function queryNeracaSaldo($k_debit, $k_kredit, $tahun, $warehouse_id)
    {
        $stat = \DB::connection(env('TENANT_DB_CONNECTION'))->statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        $query = \DB::connection(env('TENANT_DB_CONNECTION'))
            ->select(\DB::raw("SELECT tb_rekenings.id,
                                       tb_rekenings.kode,
                                       SUBSTR(tb_rekenings.kode,1,1) as short_kode,
                                       tb_rekenings.nama,
                                       tb_rekenings.depth,
                                       tb_rekenings.jenis_mutasi,
                                       COALESCE(x.dsum,0) as debit,
                                       COALESCE(x.ksum,0) as kredit
                                FROM tb_rekenings
                                LEFT JOIN(
                                    SELECT tb_rekening_kode,
                                           $k_debit AS dsum,
                                           $k_kredit AS ksum
                                    FROM tb_rekening_saldos
                                    WHERE tahun = $tahun
                                    AND warehouse_id = $warehouse_id
                                ) AS x ON x.tb_rekening_kode = tb_rekenings.kode
                                ORDER BY tb_rekenings.kode ASC
                                "));

        return $query;
    }
}
