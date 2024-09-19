@php
    use App\TbJurnal;
    $jrn = new TbJurnal();
@endphp
<table class="table table-striped" style="width: 100%;border-collapse: collapse;"
    @if (Request::get('print') == 'yes') border="1" @endif>
    @if (Request::get('xls') == 'yes')
        <tr>
            <th width="10">Kode Akun</th>
            <th width="20">Nama Akun</th>
            <th width="15">Neraca Saldo</th>
            <th width="15">Laba Rugi</th>
            <th width="15">Neraca</th>
        </tr>
    @else
        <tr>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th>Neraca Saldo</th>
            <th>Laba Rugi</th>
            <th>Neraca</th>
        </tr>
    @endif
    @php
        $t_saldo_neraca_saldo = 0;
        $t_saldo_neraca = 0;
        $t_laba_rugi = 0;
    @endphp
    @foreach ($data['neraca_saldo'] as $ky => $vl)
        @php
            $saldo_neraca_saldo = $vl->jenis_mutasi == 'debit' ? $vl->debit - $vl->kredit : $vl->kredit - $vl->debit;
            $cek_lr = array_search($vl->kode, $arr_laba_rugi) != null ? $vl->kode : '';
            $tahunx = Request::get('tahun');
            $bulanx = Request::get('bulan');
            $v_lr = '';
            $f_lr = 0;
            if ($cek_lr != '') {
                if ($vl->kode == '1.1.03.01') {
                    $lr = $jrn->queryPersediaanPembelian($bulanx, $tahunx, $warehouse_id);
                    $f_lr = $lr;
                } else {
                    $lr = $jrn->queryLabaKotor($k_debit, $k_kredit, $tahunx, $warehouse_id, $vl->kode);
                    $f_lr = $lr['saldo'];
                }

                $v_lr = number_format($f_lr) < 0 ? '(' . number_format($f_lr * -1) . ')' : number_format($f_lr);
            }

            $v_saldo_neraca_saldo =
                number_format($saldo_neraca_saldo) < 0
                    ? '(' . number_format($saldo_neraca_saldo * -1) . ')'
                    : number_format($saldo_neraca_saldo);
            $saldo_neraca = in_array($vl->short_kode, [1, 2, 3]) ? $saldo_neraca_saldo : '';

            $v_saldo_neraca = '';
            if ($saldo_neraca != '') {
                $v_saldo_neraca =
                    number_format($saldo_neraca) < 0
                        ? '(' . number_format($saldo_neraca * -1) . ')'
                        : number_format($saldo_neraca);
            }

        @endphp

        @if ($vl->depth == 0 || $vl->depth == 1)
            <tr>
                <td style="font-weight:bold;text-align: left;">{{ $vl->kode }}</td>
                <td style="font-weight:bold;">{{ $vl->nama }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @elseif($vl->depth == 2)
            <tr>
                <td style="font-weight:bold;text-align: left;">{{ $vl->kode }}</td>
                <td style="font-weight:bold;">{{ $vl->nama }}</td>
                <td style="text-align:right;"></td>
                <td style="text-align:right;"></td>
                <td style="text-align:right;"></td>
            </tr>
        @else
            <tr>
                <td>{{ $vl->kode }}</td>
                <td @if (Request::get('xls') == 'yes') width="40" @endif>{{ $vl->nama }}</td>
                <td style="text-align:right;">{{ $v_saldo_neraca_saldo }}</td>
                <td style="text-align:right;">{{ $v_lr }}</td>
                <td style="text-align:right;">{{ $v_saldo_neraca }}</td>
            </tr>
        @endif
        @php
            $t_saldo_neraca_saldo += $saldo_neraca_saldo;
            $t_saldo_neraca += in_array($vl->short_kode, [1, 2, 3]) ? $saldo_neraca_saldo : 0;
            $t_laba_rugi += $f_lr;
        @endphp
    @endforeach
    @php

        $v_t_saldo_neraca_saldo =
            number_format($t_saldo_neraca_saldo) < 0
                ? '(' . number_format($t_saldo_neraca_saldo * -1) . ')'
                : number_format($t_saldo_neraca_saldo);
        $v_t_saldo_neraca =
            number_format($t_saldo_neraca) < 0
                ? '(' . number_format($t_saldo_neraca * -1) . ')'
                : number_format($t_saldo_neraca);
        $v_t_laba_rugi =
            number_format($t_laba_rugi) < 0
                ? '(' . number_format($t_laba_rugi * -1) . ')'
                : number_format($t_laba_rugi);
    @endphp
    <tr>
        <td colspan="2" style="font-weight:bold;">Total</td>
        <td style="font-weight:bold;text-align:right;">{{ $v_t_saldo_neraca_saldo }}</td>
        <td style="font-weight:bold;text-align:right;">{{ $v_t_laba_rugi }}</td>
        <td style="font-weight:bold;text-align:right;">{{ $v_t_saldo_neraca }}</td>
    </tr>
</table>
