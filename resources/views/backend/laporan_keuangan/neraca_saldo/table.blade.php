@php
    use App\TbJurnal;
    $jrn = new TbJurnal();
@endphp
<table class="table table-striped" style="width: 100%;border-collapse: collapse;"
    @if (Request::get('print') == 'yes') border="1" @endif>
    <tr>
        <th rowspan="2" colspan="1" width="6%">Kode Akun</th>
        <th rowspan="2" colspan="1" width="40%">Nama Akun</th>
        <th rowspan="1" colspan="2" width="18%">Neraca Saldo</th>
        <th rowspan="1" colspan="2" width="18%">Laba Rugi</th>
        <th rowspan="1" colspan="2" width="18%">Neraca</th>
    </tr>
    <tr>
        <th>Debit</th>
        <th>Kredit</th>
        <th>Debit</th>
        <th>Kredit</th>
        <th>Debit</th>
        <th>Kredit</th>
    </tr>
    @php
        $t_saldo_neraca_saldo_kredit = 0;
        $t_saldo_neraca_saldo_debit = 0;
        $t_saldo_neraca_kredit = 0;
        $t_saldo_neraca_debit = 0;
        $t_laba_rugi_kredit = 0;
        $t_laba_rugi_debit = 0;
    @endphp
    @foreach ($data['neraca_saldo'] as $ky => $vl)
        @php
            $saldo_neraca_saldo = $vl->jenis_mutasi == 'debit' ? $vl->debit - $vl->kredit : $vl->kredit - $vl->debit;
            $cek_lr = array_search($vl->kode, $arr_laba_rugi) != null ? $vl->kode : '';
            $tahunx = Request::get('tahun');
            $bulanx = Request::get('bulan');
            $v_lr = 0;
            $f_lr = 0;
            if ($cek_lr != '') {
                if ($vl->kode == '1.1.03.01') {
                    $lr = $jrn->queryPersediaanPembelian($bulanx, $tahunx, $warehouse_id);
                    $f_lr = $lr;
                } else {
                    $lr = $jrn->queryLabaKotor($k_debit, $k_kredit, $tahunx, $warehouse_id, $vl->kode);
                    $f_lr = $lr['saldo'];
                }

                $v_lr = $f_lr;
            }

            $saldo_neraca = in_array($vl->short_kode, [1, 2, 3]) ? $saldo_neraca_saldo : 0;

            $v_saldo_neraca_saldo_debit = $saldo_neraca_saldo;
            $v_saldo_neraca_saldo_kredit = 0;
            if ($vl->jenis_mutasi == 'kredit') {
                $v_saldo_neraca_saldo_debit = 0;
                $v_saldo_neraca_saldo_kredit = $saldo_neraca_saldo;
            }

            $v_saldo_neraca_debit = $saldo_neraca;
            $v_saldo_neraca_kredit = 0;
            if ($vl->jenis_mutasi == 'kredit') {
                $v_saldo_neraca_debit = 0;
                $v_saldo_neraca_kredit = $saldo_neraca;
            }

            $v_laba_rugi_debit = $v_lr;
            $v_laba_rugi_kredit = 0;
            if ($vl->jenis_mutasi == 'kredit') {
                $v_laba_rugi_debit = 0;
                $v_laba_rugi_kredit = $v_lr;
            }
        @endphp

        @if ($vl->depth == 0 || $vl->depth == 1)
            <tr>
                <td style="font-weight:bold;text-align: left;">{{ $vl->kode }}</td>
                <td style="font-weight:bold;">{{ $vl->nama }}</td>
                <td></td>
                <td></td>
                <td></td>
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
                <td style="text-align:right;"></td>
                <td style="text-align:right;"></td>
                <td style="text-align:right;"></td>
            </tr>
        @else
            <tr>
                <td>{{ $vl->kode }}</td>
                <td @if (Request::get('xls') == 'yes') width="40" @endif>{{ $vl->nama }}</td>
                <td style="text-align:right;">{{ number_format($v_saldo_neraca_saldo_debit) }}</td>
                <td style="text-align:right;">{{ number_format($v_saldo_neraca_saldo_kredit) }}</td>
                <td style="text-align:right;">{{ number_format($v_laba_rugi_debit) }}</td>
                <td style="text-align:right;">{{ number_format($v_laba_rugi_kredit) }}</td>
                <td style="text-align:right;">{{ number_format($v_saldo_neraca_debit) }}</td>
                <td style="text-align:right;">{{ number_format($v_saldo_neraca_kredit) }}</td>
            </tr>
        @endif
        @php
            $t_saldo_neraca_saldo_debit += $v_saldo_neraca_saldo_debit;
            $t_saldo_neraca_saldo_kredit += $v_saldo_neraca_saldo_kredit;
            $t_saldo_neraca_debit += $v_saldo_neraca_debit;
            $t_saldo_neraca_kredit += $v_saldo_neraca_kredit;
            $t_laba_rugi_debit += $v_laba_rugi_debit;
            $t_laba_rugi_kredit += $v_laba_rugi_kredit;
        @endphp
    @endforeach

    <tr>
        <td colspan="2" style="font-weight:bold;">Total</td>
        <td style="font-weight:bold;text-align:right;">{{ number_format($t_saldo_neraca_saldo_debit) }}</td>
        <td style="font-weight:bold;text-align:right;">{{ number_format($t_saldo_neraca_saldo_kredit) }}</td>
        <td style="font-weight:bold;text-align:right;">{{ number_format($t_laba_rugi_debit) }}</td>
        <td style="font-weight:bold;text-align:right;">{{ number_format($t_laba_rugi_kredit) }}</td>
        <td style="font-weight:bold;text-align:right;">{{ number_format($t_saldo_neraca_debit) }}</td>
        <td style="font-weight:bold;text-align:right;">{{ number_format($t_saldo_neraca_kredit) }}</td>
    </tr>
</table>
