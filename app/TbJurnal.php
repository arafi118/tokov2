<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\TbKonfigurasiTransaksi;
use App\TbJurnalDetail;
use App\TbIndukJenisTransaksi;
use App\TbRekening;
use App\TbRekeningSaldo;
use App\Nota;
use App\Warehouse;
use Carbon\Carbon;

class TbJurnal extends Model
{
    use HasFactory, UsesTenantConnection;

    public function queryPersediaanPembelian($bulan, $tahun, $warehouse_id)
    {
        $query = TbJurnalDetail::selectRaw('SUM(debit_nominal) as s_debit,tb_jurnals.tgl_transaksi,debit_nominal,debit_kode,tb_jenis_transaksis.slug')
            ->leftJoin('tb_jurnals', 'tb_jurnals.id', '=', 'tb_jurnal_details.tb_jurnal_id')
            ->leftJoin('tb_jenis_transaksis', 'tb_jenis_transaksis.id', '=', 'tb_jurnal_details.tb_jenis_transaksi_id')
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->where('tb_jurnal_details.debit_kode', '1.1.03.01')
            ->whereIn('slug', ['pembelian-tunai', 'pembelian-transfer', 'pembelian-tempo', 'validasi-penerimaan-barang-po-tunai', 'validasi-penerimaan-barang-po-transfer'])
            ->first();

        return $query != null ? $query->s_debit : 0;
    }

    public function queryReturPembelian($bulan, $tahun, $warehouse_id)
    {
        $query = TbJurnalDetail::selectRaw('SUM(debit_nominal) as s_debit,tb_jurnals.tgl_transaksi,debit_nominal,debit_kode,tb_jenis_transaksis.slug')
            ->leftJoin('tb_jurnals', 'tb_jurnals.id', '=', 'tb_jurnal_details.tb_jurnal_id')
            ->leftJoin('tb_jenis_transaksis', 'tb_jenis_transaksis.id', '=', 'tb_jurnal_details.tb_jenis_transaksi_id')
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->where('tb_jurnal_details.kredit_kode', '1.1.03.01')
            ->whereIn('slug', ['retur-pembelian-tunai', 'retur-pembelian-transfer', 'retur-pembelian-tempo'])
            ->first();

        return $query != null ? $query->s_debit : 0;
    }

    public function queryPersediaanAwal($bulan_lalu, $tahun_lalu, $tahun, $warehouse_id)
    {
        $rs = new TbRekeningSaldo;
        $q_tahun_lalu = $rs->getSaldoAwalTahunLalu('1.1.03.01', $tahun_lalu, $warehouse_id);
        $q_bulan_lalu = $rs->getSaldoBulanLalu('1.1.03.01', $tahun, $bulan_lalu, $warehouse_id);

        $pa_tahun_lalu = 0;

        if ($q_tahun_lalu != null) {

            $pa_tahun_lalu = $q_tahun_lalu->debit - $q_tahun_lalu->kredit;
        }

        $pa_bulan_lalu = 0;

        if ($q_bulan_lalu != null) {

            $pa_bulan_lalu = $q_bulan_lalu->debit - $q_bulan_lalu->kredit;
        }

        $t_awal = $pa_tahun_lalu + $pa_bulan_lalu;

        return $t_awal;
    }

    public function queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, $kode)
    {

        $stat = \DB::connection(env('TENANT_DB_CONNECTION'))->statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");


        $query = \DB::connection(env('TENANT_DB_CONNECTION'))
            ->select(\DB::raw("SELECT tb_rekenings.id,
                                    tb_rekenings.kode,
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
                                WHERE tb_rekenings.kode = '$kode'
                                ORDER BY tb_rekenings.kode ASC
                                "));

        $result = [
            'kode' => $query[0]->kode,
            'nama' => $query[0]->nama,
            'saldo' => $query[0]->jenis_mutasi == 'kredit' ? $query[0]->kredit - $query[0]->debit : $query[0]->debit - $query[0]->kredit
        ];

        return $result;
    }

    public function queryRugiLaba($k_debit, $k_kredit, $base_kode, $tahun, $warehouse_id)
    {
        $stat = \DB::connection(env('TENANT_DB_CONNECTION'))->statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        $query = \DB::connection(env('TENANT_DB_CONNECTION'))
            ->select(\DB::raw("SELECT tb_rekenings.id,
                                    tb_rekenings.kode,
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
                                WHERE tb_rekenings.kode LIKE '$base_kode%' 
                                AND tb_rekenings.depth = 3
                                ORDER BY tb_rekenings.kode ASC
                                "));

        return $query;
    }

    public function notaCounter($jenis_transaksi)
    {
        $trans = Nota::selectRaw('*')->where('jenis_transaksi', str_replace('_', '-', $jenis_transaksi))->first()->toArray();

        $u      = $trans['kode'];
        $table  = $trans['table'];
        $column = $table == 'payments' ? 'payment_reference' : 'reference_no';

        $column = 'reference_no';

        if ($table == 'payments') {
            $column = 'payment_reference';
        } elseif ($table == 'tb_jurnals') {
            $column = 'nomor_transaksi';
        }

        $max  = \DB::connection(env('TENANT_DB_CONNECTION'))->table($table)->where($column, 'like', '%' . $u . '-%')->max($column);

        if ($max == null) {
            $final_kode = $u . '-0000001';
        } else {
            $max++;
            $final_kode = sprintf("%06s", $max);
        }

        return $final_kode;
    }

    public function getDetailsGrouped($jm, $tb_jurnal_id)
    {
        $rek = TbJurnalDetail::selectRaw($jm . '_nominal,' . $jm . '_kode,nama as ' . $jm . ',SUM(' . $jm . '_nominal) as ' . $jm . '_t_nominal')
            ->leftJoin('tb_rekenings', 'tb_rekenings.kode', '=', 'tb_jurnal_details.' . $jm . '_kode')
            ->groupBy($jm . '_kode')
            ->where('tb_jurnal_details.tb_jurnal_id', $tb_jurnal_id)
            ->get();

        return $rek;
    }

    public function induk()
    {
        return $this->belongsTo('App\TbIndukJenisTransaksi', 'tb_induk_jenis_transaksi_id', 'id');
    }

    public function pic()
    {
        return $this->belongsTo('App\User', 'insertedby', 'id');
    }

    public function details()
    {
        return $this->hasMany('App\TbJurnalDetail');
    }

    public function getKomulatifDebitKredit($kd_akun, $warehouse_id = null)
    {
        $sql_d = SELF::selectRaw('SUM(tb_jurnal_details.debit_nominal) AS kom_debit')
            ->leftJoin('tb_jurnal_details', 'tb_jurnals.id', '=', 'tb_jurnal_details.tb_jurnal_id')
            ->leftJoin('tb_rekenings as debit', 'debit.kode', '=', 'tb_jurnal_details.debit_kode');


        if ($warehouse_id != null) {
            $sql_d->where('tb_jurnals.warehouse_id', $warehouse_id);
        }

        $query_d = $sql_d->where('tb_jurnal_details.debit_kode', $kd_akun)->first();

        $kom_debit = $query_d->kom_debit != null ? $query_d->kom_debit : 0;

        $sql_k = SELF::selectRaw('SUM(tb_jurnal_details.kredit_nominal) AS kom_kredit')
            ->leftJoin('tb_jurnal_details', 'tb_jurnals.id', '=', 'tb_jurnal_details.tb_jurnal_id')
            ->leftJoin('tb_rekenings as kredit', 'kredit.kode', '=', 'tb_jurnal_details.kredit_kode');

        if ($warehouse_id != null) {
            $sql_k->where('tb_jurnals.warehouse_id', $warehouse_id);
        }

        $query_k = $sql_k->where('tb_jurnal_details.kredit_kode', $kd_akun)->first();

        $kom_kredit = $query_k->kom_kredit != null ? $query_k->kom_kredit : 0;

        $data = compact('kom_debit', 'kom_kredit');

        return $data;
    }

    public function getSaldoAkun($kd_akun, $jenis_mutasi, $warehouse_id = null)
    {
        $sql = SELF::selectRaw('tb_rekenings.kode,tb_rekenings.nama,tb_jurnal_details.debit_nominal, tb_jurnal_details.kredit_nominal,tb_jurnal_details.deskripsi')
            ->leftJoin('tb_jurnal_details', 'tb_jurnals.id', '=', 'tb_jurnal_details.tb_jurnal_id');

        if ($jenis_mutasi == 'debit') {
            $sql->leftJoin('tb_rekenings', 'tb_rekenings.kode', '=', 'tb_jurnal_details.debit_kode');
        }

        if ($jenis_mutasi == 'kredit') {
            $sql->leftJoin('tb_rekenings', 'tb_rekenings.kode', '=', 'tb_jurnal_details.kredit_kode');
        }

        $sql->where('tb_rekenings.kode', $kd_akun);
        if ($warehouse_id != null) {
            $sql->where('tb_jurnals.warehouse_id', $warehouse_id);
        }
        $data = $sql->get();

        $saldo = 0;
        foreach ($data as $key) {
            if ($jenis_mutasi == 'debit') {
                $saldo += $key->debit_nominal;
            } elseif ($jenis_mutasi == 'kredit') {
                $saldo += $key->kredit_nominal;
            }
        }

        return $saldo;
    }

    public function simpan(array $data)
    {
        try {
            $induk = TbIndukJenisTransaksi::where('nama', $data['induk_transaksi'])->first();

            $cek_existing = TbJurnal::where('referensi_id', $data['referensi_id'])
                ->where('tabel_transaksi', $data['tabel_transaksi'])
                ->where('nomor_transaksi', $data['nomor_transaksi'])
                ->get()
                ->count();

            if ($cek_existing > 0) {

                $ck = TbJurnal::selectRaw('*');

                if ($data['induk_transaksi'] == 'Stok Opname') {
                    $ck->where('nomor_transaksi', $data['nomor_transaksi']);
                } else {
                    $ck->where('referensi_id', $data['referensi_id'])
                        ->where('tabel_transaksi', $data['tabel_transaksi']);
                }

                $ck->delete();
            }

            $jurnal = new TbJurnal;
            $jurnal->warehouse_id = $data['gudang'];
            $jurnal->tb_induk_jenis_transaksi_id = $induk->id;
            $jurnal->tgl_transaksi               = Carbon::parse($data['tanggal_transaksi'])->format('Y-m-d');
            $jurnal->nomor_transaksi             = $data['nomor_transaksi'];
            $jurnal->memo                        = $data['memo'];
            $jurnal->tabel_transaksi = $data['tabel_transaksi'];
            $jurnal->referensi_id    = $data['referensi_id'];
            $jurnal->insertedby      = auth()->user()->id;
            $jurnal->save();

            $jenis_transaksi = array();

            foreach ($data['details'] as $key => $value) {

                if ($value['nominal'] != null && $value['nominal'] > 0) {

                    $konf = $this->getKonfAkun(['slug' => $value['slug'], 'cara_bayar' => $value['cara_bayar']]);

                    if ($konf == null) {
                        dd($value);
                    }

                    $j_detail = new TbJurnalDetail;
                    $j_detail->tb_jurnal_id = $jurnal->id;
                    $j_detail->tb_jenis_transaksi_id = $konf->tb_jenis_transaksi_id;
                    $j_detail->debit_kode     = $konf->debit_kode;
                    $j_detail->kredit_kode    = $konf->kredit_kode;
                    $j_detail->debit_nominal  = $value['nominal'];
                    $j_detail->kredit_nominal = $value['nominal'];
                    $j_detail->deskripsi      = $value['label'];
                    $j_detail->save();

                    $params  = [
                        'debit_kode'         => $konf->debit_kode,
                        'debit_jenis_mutasi' => $konf->debit_jenis_mutasi,
                        'kredit_kode'        => $konf->kredit_kode,
                        'kredit_jenis_mutasi' => $konf->kredit_jenis_mutasi,
                        'gudang'             => $data['gudang'],
                        'tanggal_transaksi'  => $data['tanggal_transaksi']
                    ];

                    $this->updateSaldo($params);
                    $this->updateLabaRugi($params);
                }
            }

            $response = true;
        } catch (\Exception $e) {
            \Log::error("jurnal save: {$e->getMessage()}");

            // Response Message
            $response = false;
            return abort(500);
        }

        return $response;
    }

    private function getKonfAkun(array $params)
    {
        $query = TbKonfigurasiTransaksi::selectRaw('tb_konfigurasi_transaksis.*,
                                                    tb_jenis_transaksis.slug,
                                                    tb_jenis_transaksis.nama,
                                                    debit.kode as debit_kode,
                                                    debit.nama as debit_nama,
                                                    debit.jenis_mutasi as debit_jenis_mutasi,
                                                    kredit.kode as kredit_kode,
                                                    kredit.nama as kredit_nama,
                                                    kredit.jenis_mutasi as kredit_jenis_mutasi
                                                    ')
            ->leftJoin('tb_jenis_transaksis', 'tb_jenis_transaksis.id', '=', 'tb_konfigurasi_transaksis.tb_jenis_transaksi_id')
            ->leftJoin('tb_rekenings as debit', 'debit.kode', '=', 'tb_konfigurasi_transaksis.rekening_debit_kode')
            ->leftJoin('tb_rekenings as kredit', 'kredit.kode', '=', 'tb_konfigurasi_transaksis.rekening_kredit_kode')
            ->where('tb_jenis_transaksis.slug', $params['slug']);

        if ($params['cara_bayar'] != null) {
            $query->where('cara_bayar', $params['cara_bayar']);
        }

        $konf = $query->first();

        return $konf;
    }

    public function updateLabaRugi(array $params, $tamp = null)
    {
        $warehouse_id = $params['gudang'];
        $tahun        = Carbon::parse($params['tanggal_transaksi'])->format('Y');
        $bulan        = Carbon::parse($params['tanggal_transaksi'])->format('m');
        $k_debit    = 'debit_' . $bulan;
        $k_kredit   = 'kredit_' . $bulan;
        $tahun_lalu = $tahun - 1;
        $xbulan     = $bulan - 1;
        $bulan_lalu = strlen($xbulan) > 1 ? $xbulan : '0' . $xbulan;

        $data['penjualan']        = $this->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '4.1.01.01');
        $data['pot_penjualan']    = $this->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '4.1.01.02');
        $data['retur_penjualan']  = $this->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '4.1.01.03');
        $data['persediaan_awal']  = $this->queryPersediaanAwal($bulan_lalu, $tahun_lalu, $tahun, $warehouse_id);
        $data['persediaan']       = $this->queryPersediaanPembelian($bulan, $tahun, $warehouse_id);
        $data['beban_pengolahan'] = $this->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '5.1.01.04');
        $data['beban_angkut']     = $this->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '5.1.01.05');
        $data['pot_pembelian']    = $this->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '5.1.01.02');
        $data['retur_pembelian']  = $this->queryReturPembelian($bulan, $tahun, $warehouse_id);
        $data['persediaan_akhir'] = $this->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '1.1.03.01');
        $data['pendapatan_lain_lain'] = $this->queryLabaKotor($k_debit, $k_kredit, $tahun, $warehouse_id, '4.1.01.05');

        $data['beban_operasional'] = $this->loopBebanPendapatan($k_debit, $k_kredit, '6.1', $tahun, $warehouse_id);
        $pendapatan_non_usaha      = $this->loopBebanPendapatan($k_debit, $k_kredit, '7.1', $tahun, $warehouse_id);
        $pendapatan_non_usaha_2    = $this->loopBebanPendapatan($k_debit, $k_kredit, '7.2', $tahun, $warehouse_id);
        $data['pendapatan_non_usaha']   = $pendapatan_non_usaha + $pendapatan_non_usaha_2;
        $data['beban_non_usaha']   = $this->loopBebanPendapatan($k_debit, $k_kredit, '7.3', $tahun, $warehouse_id);
        $data['beban_pajak']       = $this->loopBebanPendapatan($k_debit, $k_kredit, '7.4', $tahun, $warehouse_id);

        $data['penjualan_bersih'] = $data['penjualan']['saldo'] + $data['pot_penjualan']['saldo'] + $data['retur_penjualan']['saldo'];
        $data['total_pembelian']  = $data['persediaan'] + $data['beban_pengolahan']['saldo'] + $data['beban_angkut']['saldo'] - $data['pot_pembelian']['saldo'];

        $data['total_persediaan'] = $data['persediaan_awal'] + $data['total_pembelian'];
        $data['persediaan_akhir'] = $data['persediaan_awal'] + $data['persediaan_akhir']['saldo'] + $data['retur_pembelian'];
        $data['hpp']              = $data['total_persediaan'] - $data['persediaan_akhir'];
        $data['laba_kotor']       = $data['penjualan_bersih'] - $data['hpp'];
        $data['laba_rugi_sebelum_pajak'] = $data['laba_kotor'] -  $data['beban_operasional'] + $data['pendapatan_non_usaha'] + $data['pendapatan_lain_lain']['saldo'] - $data['beban_non_usaha'];
        $data['laba_rugi']        = $data['laba_rugi_sebelum_pajak'] - $data['beban_pajak'];

        if ($tamp == null) {
            $cek_komulatif_k = TbRekeningSaldo::where('tahun', $tahun)
                ->where('warehouse_id', $warehouse_id)
                ->where('tb_rekening_kode', '3.2.01.02')
                ->first();

            $data_k = [
                'tb_rekening_kode'    => '3.2.01.02',
                'tahun'               => $tahun,
                'debit_' . $bulan       => 0,
                'kredit_' . $bulan      => $data['laba_rugi'],
                'warehouse_id'        => $warehouse_id
            ];

            if ($cek_komulatif_k != null) {
                TbRekeningSaldo::where('tahun', $tahun)
                    ->where('warehouse_id', $warehouse_id)
                    ->where('tb_rekening_kode', '3.2.01.02')
                    ->update($data_k);
            } else {
                TbRekeningSaldo::insert($data_k);
            }
        } else {
            return $data;
        }
    }

    private function loopBebanPendapatan($k_debit, $k_kredit, $kode, $tahun, $warehouse_id)
    {
        $q = $this->queryRugiLaba($k_debit, $k_kredit, $kode, $tahun, $warehouse_id);

        $ttl = 0;
        foreach ($q as $ky) {
            $saldo = $ky->jenis_mutasi == 'debit' ? $ky->debit - $ky->kredit : $ky->kredit - $ky->debit;
            $ttl += $saldo;
        }

        return $ttl;
    }

    public function updateSaldo(array $params)
    {
        /*$last_saldo_d = $this->getSaldoAkun($params['debit_kode'],$params['debit_jenis_mutasi'],$params['gudang']);
        $last_saldo_k = $this->getSaldoAkun($params['kredit_kode'],$params['kredit_jenis_mutasi'],$params['gudang']);

        $saldo_d = TbRekening::where('kode',$params['debit_kode'])->first();
        $saldo_d->saldo = $last_saldo_d;
        $saldo_d->save();

        $saldo_k = TbRekening::where('kode',$params['kredit_kode'])->first();
        $saldo_k->saldo = $last_saldo_k;
        $saldo_k->save();*/

        $last_komulatif_d = $this->getKomulatifDebitKredit($params['debit_kode'], $params['gudang']);
        $last_komulatif_k = $this->getKomulatifDebitKredit($params['kredit_kode'], $params['gudang']);

        $tahun = Carbon::parse($params['tanggal_transaksi'])->format('Y');
        $bulan = Carbon::parse($params['tanggal_transaksi'])->format('m');

        $cek_komulatif_d = TbRekeningSaldo::where('tahun', $tahun)
            ->where('warehouse_id', $params['gudang'])
            ->where('tb_rekening_kode', $params['debit_kode'])
            ->first();

        $data_d = [
            'tb_rekening_kode'    => $params['debit_kode'],
            'tahun'               => $tahun,
            'debit_' . $bulan       => $last_komulatif_d['kom_debit'],
            'kredit_' . $bulan      => $last_komulatif_d['kom_kredit'],
            'warehouse_id'        => $params['gudang']
        ];

        if ($cek_komulatif_d != null) {
            TbRekeningSaldo::where('tahun', $tahun)
                ->where('warehouse_id', $params['gudang'])
                ->where('tb_rekening_kode', $params['debit_kode'])
                ->update($data_d);
        } else {
            TbRekeningSaldo::insert($data_d);
        }

        $cek_komulatif_k = TbRekeningSaldo::where('tahun', $tahun)
            ->where('warehouse_id', $params['gudang'])
            ->where('tb_rekening_kode', $params['kredit_kode'])
            ->first();

        $data_k = [
            'tb_rekening_kode'    => $params['kredit_kode'],
            'tahun'               => $tahun,
            'debit_' . $bulan       => $last_komulatif_k['kom_debit'],
            'kredit_' . $bulan      => $last_komulatif_k['kom_kredit'],
            'warehouse_id'        => $params['gudang']
        ];

        if ($cek_komulatif_k != null) {
            TbRekeningSaldo::where('tahun', $tahun)
                ->where('warehouse_id', $params['gudang'])
                ->where('tb_rekening_kode', $params['kredit_kode'])
                ->update($data_k);
        } else {
            TbRekeningSaldo::insert($data_k);
        }
    }

    public function hapus($referensi_id, $tabel_transaksi)
    {
        $dataJurnal = TbJurnal::with(['details' => function ($q) {
            $q->with('rek_debit')->with('rek_kredit');
        }])
            ->where('referensi_id', $referensi_id)
            ->where('tabel_transaksi', $tabel_transaksi)
            ->first();

        if ($dataJurnal != null) {
            $this->doHapus($dataJurnal);
        }
    }

    public function hapus_by_ref($ref_code)
    {
        $dataJurnal = TbJurnal::with(['details' => function ($q) {
            $q->with('rek_debit')->with('rek_kredit');
        }])
            ->where('nomor_transaksi', $ref_code)
            ->first();

        if ($dataJurnal != null) {
            $this->doHapus($dataJurnal);
        }
    }

    private function doHapus(TbJurnal $dataJurnal)
    {
        //ambil data transaksi yang dimaksud

        $params = array();

        foreach ($dataJurnal->details as $key) {
            //simpan di variabel
            $params[]  = [
                'debit_kode'        => $key->debit_kode,
                'debit_jenis_mutasi' => $key->rek_debit->jenis_mutasi,
                'kredit_kode'        => $key->kredit_kode,
                'kredit_jenis_mutasi' => $key->rek_kredit->jenis_mutasi,
                'gudang'             => $dataJurnal->warehouse_id,
                'tanggal_transaksi'  => $dataJurnal->tgl_transaksi
            ];
        }

        //delete transaksi di tabel jurnal sehingga komulatif berubah

        $dataJurnal->delete();

        //get last saldo & komulatif , setelah update saldo dan komulatif 
        foreach ($params as $p) {
            $this->updateSaldo($p);
            $this->updateLabaRugi($p);
        }
    }
}
