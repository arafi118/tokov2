<table class="table table-striped" style="width: 100%;border-collapse: collapse;"
    @if (Request::get('print') == 'yes') border="1" @endif>
    @php
        $t_saldo_aset = 0;
    @endphp
    @foreach ($data['aset'] as $ky)
        @php
            $saldo_aset = $ky->jenis_mutasi == 'debit' ? $ky->debit - $ky->kredit : $ky->kredit - $ky->debit;
            $d_saldo_aset =
                number_format($saldo_aset) < 0
                    ? '(' . number_format($saldo_aset * -1) . ')'
                    : number_format($saldo_aset);
        @endphp
        @if ($ky->depth == 0)
            <tr>
                <td colspan="3" style="text-align:center;font-weight:bold;background: #eee;">
                    {{ $ky->parent_code }}.{{ $ky->parent }}
                </td>
            </tr>
        @elseif($ky->depth == 1)
            <tr>
                <td style="font-weight:bold;text-align: left;">{{ $ky->parent_code }}</td>
                <td colspan="2" style="font-weight:bold;">{{ $ky->parent }}</td>
            </tr>
        @else
            <tr>
                <td width="10%">{{ $ky->parent_code }}</td>
                <td {!! Request::get('xls') == 'yes' ? 'width="40"' : '' !!}>{{ $ky->parent }}</td>
                <td width="15%" style="text-align:right;">{{ $d_saldo_aset }}</td>
            </tr>
        @endif
        @php
            $t_saldo_aset += $saldo_aset;
        @endphp
    @endforeach
    @php
        $d_t_saldo_aset =
            number_format($t_saldo_aset) < 0
                ? '(' . number_format($t_saldo_aset * -1) . ')'
                : number_format($t_saldo_aset);
    @endphp
    <tr>
        <td colspan="2" style="font-weight:bold;">Jumlah Aset</td>
        <td style="font-weight:bold;text-align:right;">{{ $d_t_saldo_aset }}</td>
    </tr>
    @php
        $t_saldo_utang = 0;
    @endphp
    @foreach ($data['utang'] as $ky)
        @php
            $saldo_utang = $ky->jenis_mutasi == 'debit' ? $ky->debit - $ky->kredit : $ky->kredit - $ky->debit;
            $d_saldo_utang =
                number_format($saldo_utang) < 0
                    ? '(' . number_format($saldo_utang * -1) . ')'
                    : number_format($saldo_utang);
        @endphp
        @if ($ky->depth == 0)
            <tr>
                <td colspan="3" style="text-align:center;font-weight:bold;background: #eee;">
                    {{ $ky->parent_code }}.{{ $ky->parent }}</td>
            </tr>
        @elseif($ky->depth == 1)
            <tr>
                <td style="font-weight:bold;">{{ $ky->parent_code }}</td>
                <td colspan="2" style="font-weight:bold;">{{ $ky->parent }}</td>
            </tr>
        @else
            <tr>
                <td>{{ $ky->parent_code }}</td>
                <td>{{ $ky->parent }}</td>
                <td style="text-align:right;">{{ $d_saldo_utang }}</td>
            </tr>
        @endif
        @php
            $t_saldo_utang += $saldo_utang;
        @endphp
    @endforeach
    @php
        $d_t_saldo_utang =
            number_format($t_saldo_utang) < 0
                ? '(' . number_format($t_saldo_utang * -1) . ')'
                : number_format($t_saldo_utang);
    @endphp
    <tr>
        <td colspan="2" style="font-weight:bold;">Jumlah Utang</td>
        <td style="font-weight:bold;text-align:right;">{{ $d_t_saldo_utang }}</td>
    </tr>
    @php
        $t_saldo_modal = 0;
    @endphp
    @foreach ($data['modal'] as $ky)
        @php
            $saldo_modal = $ky->jenis_mutasi == 'debit' ? $ky->debit - $ky->kredit : $ky->kredit - $ky->debit;
            $d_saldo_modal =
                number_format($saldo_modal) < 0
                    ? '(' . number_format($saldo_modal * -1) . ')'
                    : number_format($saldo_modal);
        @endphp
        @if ($ky->depth == 0)
            <tr>
                <td colspan="3" style="text-align:center;font-weight:bold;background: #eee;">
                    {{ $ky->parent_code }}.{{ $ky->parent }}</td>
            </tr>
        @elseif($ky->depth == 1)
            <tr>
                <td style="font-weight:bold;">{{ $ky->parent_code }}</td>
                <td colspan="2" style="font-weight:bold;">{{ $ky->parent }}</td>
            </tr>
        @else
            <tr>
                <td>{{ $ky->parent_code }}</td>
                <td>{{ $ky->parent }}</td>
                <td style="text-align:right;">{{ $d_saldo_modal }}</td>
            </tr>
        @endif
        @php
            $t_saldo_modal += $saldo_modal;
        @endphp
    @endforeach
    @php
        $d_t_saldo_modal =
            number_format($t_saldo_modal) < 0
                ? '(' . number_format($t_saldo_modal * -1) . ')'
                : number_format($t_saldo_modal);
        $liabilitas_modal = $t_saldo_utang + $t_saldo_modal;
        $d_liabilitas_modal =
            number_format($liabilitas_modal) < 0
                ? '(' . number_format($liabilitas_modal * -1) . ')'
                : number_format($liabilitas_modal);
    @endphp
    <tr>
        <td colspan="2" style="font-weight:bold;">Jumlah Modal</td>
        <td style="font-weight:bold;text-align:right;">{{ $d_t_saldo_modal }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight:bold;">Jumlah Liabilitas + Ekuitas </td>
        <td style="font-weight:bold;text-align:right;">{{ $d_liabilitas_modal }}</td>
    </tr>
</table>
