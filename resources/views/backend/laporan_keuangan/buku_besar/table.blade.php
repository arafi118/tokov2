<table class="table table-striped" style="width: 100%; border-collapse: collapse;"
    @if (Request::get('print') == 'yes') border="1" @endif>
    <thead>
        @if (Request::get('xls') == 'yes')
            <tr style="background:#eee;">
                <th width="5" style="font-weight:bold;">No</th>
                <th width="10" style="font-weight:bold;">Tanggal</th>
                <th width="18" style="font-weight:bold;">Ref ID</th>
                <th width="50" style="font-weight:bold;">Keterangan</th>
                <th width="15" style="font-weight:bold;">Debit</th>
                <th width="15" style="font-weight:bold;">Kredit</th>
                <th width="15" style="font-weight:bold;">Saldo</th>
                <th width="5" style="font-weight:bold;">P</th>
            </tr>
        @else
            <tr style="background:#eee;">
                <th width="4%" height="30">No</th>
                <th width="10%">Tanggal</th>
                <th width="11%">Ref ID</th>
                <th>Keterangan</th>
                <th width="12%">Debit</th>
                <th width="12%">Kredit</th>
                <th width="12%">Saldo</th>
                <th width="3%">P</th>
            </tr>
        @endif
    </thead>
    <tbody>
        @php
            $debit_tahun_lalu = $data['saldo_tahun_lalu'] != null ? $data['saldo_tahun_lalu']->debit : 0;
            $kredit_tahun_lalu = $data['saldo_tahun_lalu'] != null ? $data['saldo_tahun_lalu']->kredit : 0;
            $saldo_tahun_lalu =
                $rekening_info->jenis_mutasi == 'debit'
                    ? $debit_tahun_lalu - $kredit_tahun_lalu
                    : $kredit_tahun_lalu - $debit_tahun_lalu;
            $debit_bulan_lalu = $data['saldo_bulan_lalu'] != null ? $data['saldo_bulan_lalu']->debit : 0;
            $kredit_bulan_lalu = $data['saldo_bulan_lalu'] != null ? $data['saldo_bulan_lalu']->kredit : 0;
            $saldo_bulan_lalu =
                $saldo_tahun_lalu +
                ($rekening_info->jenis_mutasi == 'debit'
                    ? $debit_bulan_lalu - $kredit_bulan_lalu
                    : $kredit_bulan_lalu - $debit_bulan_lalu);

            $v_debit_tahun_lalu =
                number_format($debit_tahun_lalu) < 0
                    ? '(' . number_format($debit_tahun_lalu * -1) . ')'
                    : number_format($debit_tahun_lalu);
            $v_kredit_tahun_lalu =
                number_format($kredit_tahun_lalu) < 0
                    ? '(' . number_format($kredit_tahun_lalu * -1) . ')'
                    : number_format($kredit_tahun_lalu);
            $v_saldo_tahun_lalu =
                number_format($saldo_tahun_lalu) < 0
                    ? '(' . number_format($saldo_tahun_lalu * -1) . ')'
                    : number_format($saldo_tahun_lalu);
            $v_debit_bulan_lalu =
                number_format($debit_bulan_lalu) < 0
                    ? '(' . number_format($debit_bulan_lalu * -1) . ')'
                    : number_format($debit_bulan_lalu);
            $v_kredit_bulan_lalu =
                number_format($kredit_bulan_lalu) < 0
                    ? '(' . number_format($kredit_bulan_lalu * -1) . ')'
                    : number_format($kredit_bulan_lalu);
            $v_saldo_bulan_lalu =
                number_format($saldo_bulan_lalu) < 0
                    ? '(' . number_format($saldo_bulan_lalu * -1) . ')'
                    : number_format($saldo_bulan_lalu);
        @endphp
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Komulatif Transaksi Awal Tahun</td>
            <td style="text-align:right;">{{ $v_debit_tahun_lalu }}</td>
            <td style="text-align:right;">{{ $v_kredit_tahun_lalu }}</td>
            <td style="text-align:right;">{{ $v_saldo_tahun_lalu }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Komulatif Transaksi s/d Bulan Lalu</td>
            <td style="text-align:right;">{{ $v_debit_bulan_lalu }}</td>
            <td style="text-align:right;">{{ $v_kredit_bulan_lalu }}</td>
            <td style="text-align:right;">{{ $v_saldo_bulan_lalu }}</td>
            <td></td>
        </tr>
        @php
            $xsaldo = $saldo_bulan_lalu;
            $xdebit = 0;
            $xkredit = 0;
            $v_xdebit = 0;
            $v_xkredit = 0;
            $v_xsaldo = 0;
        @endphp
        @foreach ($data['transaksi'] as $bb)
            @php
                $debit_nominal = $jenis_buku_id == $bb->debit_kode ? $bb->debit_nominal : 0;
                $kredit_nominal = $jenis_buku_id == $bb->kredit_kode ? $bb->kredit_nominal : 0;

                $v_debit_nominal =
                    number_format($debit_nominal) < 0
                        ? '(' . number_format($debit_nominal * -1) . ')'
                        : number_format($debit_nominal);
                $v_kredit_nominal =
                    number_format($kredit_nominal) < 0
                        ? '(' . number_format($kredit_nominal * -1) . ')'
                        : number_format($kredit_nominal);

                $saldo =
                    $rekening_info->jenis_mutasi == 'debit'
                        ? $debit_nominal - $kredit_nominal
                        : $kredit_nominal - $debit_nominal;

                $xsaldo += $saldo;
                $xdebit += $debit_nominal;
                $xkredit += $kredit_nominal;

                $v_xsaldo =
                    number_format($xsaldo) < 0 ? '(' . number_format($xsaldo * -1) . ')' : number_format($xsaldo);
                $v_xdebit =
                    number_format($xdebit) < 0 ? '(' . number_format($xdebit * -1) . ')' : number_format($xdebit);
                $v_xkredit =
                    number_format($xkredit) < 0 ? '(' . number_format($xkredit * -1) . ')' : number_format($xkredit);
            @endphp
            <tr>
                <td style="text-align:center;">{{ $loop->iteration }}.</td>
                <td style="text-align:center;">
                    {{ \Carbon\Carbon::parse($bb->tgl_transaksi)->format('d/m/Y') }}
                </td>
                <td style="text-align:center;">{{ strtoupper($bb->nomor_transaksi) }}</td>
                <td>{{ $bb->deskripsi }}</td>
                <td style="text-align:right;">{{ $v_debit_nominal }}</td>
                <td style="text-align:right;">{{ $v_kredit_nominal }}</td>
                <td style="text-align:right;">{{ $v_xsaldo }}</td>
                <td style="text-align:center;">{{ $bb->initial }}</td>
            </tr>
        @endforeach
        @php
            $d_sd_bulan_ini = $debit_bulan_lalu + $xdebit;
            $d_kom_tahun_ini = $debit_tahun_lalu + $d_sd_bulan_ini;
            $k_sd_bulan_ini = $kredit_bulan_lalu + $xkredit;
            $k_kom_tahun_ini = $kredit_tahun_lalu + $k_sd_bulan_ini;

            $v_d_sd_bulan_ini =
                number_format($d_sd_bulan_ini) < 0
                    ? '(' . number_format($d_sd_bulan_ini * -1) . ')'
                    : number_format($d_sd_bulan_ini);
            $v_d_kom_tahun_ini =
                number_format($d_kom_tahun_ini) < 0
                    ? '(' . number_format($d_kom_tahun_ini * -1) . ')'
                    : number_format($d_kom_tahun_ini);
            $v_k_sd_bulan_ini =
                number_format($k_sd_bulan_ini) < 0
                    ? '(' . number_format($k_sd_bulan_ini * -1) . ')'
                    : number_format($k_sd_bulan_ini);
            $v_k_kom_tahun_ini =
                number_format($k_kom_tahun_ini) < 0
                    ? '(' . number_format($k_kom_tahun_ini * -1) . ')'
                    : number_format($k_kom_tahun_ini);
        @endphp
        <tr>
            <td colspan="4" style="font-weight: bold;">Total Transaksi Bulan {{ bulan($bulan) . ' ' . $tahun }}</td>
            <td style="font-weight: bold;text-align:right;">{{ $v_xdebit }}</td>
            <td style="font-weight: bold;text-align:right;">{{ $v_xkredit }}</td>
            <td style="font-weight: bold; text-align: center;" colspan="2">Saldo : </td>
        </tr>
        <tr>
            <td colspan="4" style="font-weight: bold;">Total Transaksi s/d Bulan {{ bulan($bulan) . ' ' . $tahun }}
            </td>
            <td style="font-weight: bold;text-align:right;">{{ $v_d_sd_bulan_ini }}</td>
            <td style="font-weight: bold;text-align:right;">{{ $v_k_sd_bulan_ini }}</td>
            <td colspan="2" rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle;">
                <h3>{{ $v_xsaldo }}</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="font-weight: bold;">Total Transaksi Komulatif s/d Tahun {{ $tahun }}</td>
            <td style="font-weight: bold;text-align:right;">{{ $v_d_kom_tahun_ini }}</td>
            <td style="font-weight: bold;text-align:right;">{{ $v_k_kom_tahun_ini }}</td>
        </tr>
    </tbody>
</table>
