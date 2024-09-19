@php
    $d_penjualan =
        number_format($data['laba_kotor']['penjualan']['saldo']) < 0
            ? '(' . number_format($data['laba_kotor']['penjualan']['saldo'] * -1) . ')'
            : number_format($data['laba_kotor']['penjualan']['saldo']);
    $d_pot_penjualan =
        number_format($data['laba_kotor']['pot_penjualan']['saldo']) < 0
            ? '(' . number_format($data['laba_kotor']['pot_penjualan']['saldo'] * -1) . ')'
            : number_format($data['laba_kotor']['pot_penjualan']['saldo']);
    $d_retur_penjualan =
        number_format($data['laba_kotor']['retur_penjualan']['saldo']) < 0
            ? '(' . number_format($data['laba_kotor']['retur_penjualan']['saldo'] * -1) . ')'
            : number_format($data['laba_kotor']['retur_penjualan']['saldo']);

@endphp
<table class="table table-striped" style="width: 100%;border-collapse: collapse;" {!! Request::get('print') == 'yes' ? 'border="1"' : '' !!}>
    <tr>
        <td colspan="3" style="text-align:center;font-weight:bold;background: #eee;">LABA KOTOR</td>
    </tr>
    <tr>
        <td width="10%">{{ $data['laba_kotor']['penjualan']['kode'] }}</td>
        <td>{{ $data['laba_kotor']['penjualan']['nama'] }}</td>
        <td width="15%" style="text-align:right;">{{ $d_penjualan }}</td>
    </tr>
    <tr>
        <td>{{ $data['laba_kotor']['pot_penjualan']['kode'] }}</td>
        <td>{{ $data['laba_kotor']['pot_penjualan']['nama'] }}</td>
        <td style="text-align:right;">{{ $d_pot_penjualan }}</td>
    </tr>
    <tr>
        <td>{{ $data['laba_kotor']['retur_penjualan']['kode'] }}</td>
        <td>{{ $data['laba_kotor']['retur_penjualan']['nama'] }}
        </td>
        <td style="text-align:right;">{{ $d_retur_penjualan }}</td>
    </tr>

    @php
        $penjualan_bersih =
            $data['laba_kotor']['penjualan']['saldo'] +
            $data['laba_kotor']['pot_penjualan']['saldo'] +
            $data['laba_kotor']['retur_penjualan']['saldo'];
        $d_penjualan_bersih =
            $penjualan_bersih < 0
                ? '(' . number_format($penjualan_bersih * -1) . ')'
                : number_format($penjualan_bersih);
        $d_persediaan_awal =
            $data['laba_kotor']['persediaan_awal'] < 0
                ? '(' . number_format($data['laba_kotor']['persediaan_awal'] * -1) . ')'
                : number_format($data['laba_kotor']['persediaan_awal']);
        $d_persediaan =
            $data['laba_kotor']['persediaan'] < 0
                ? '(' . number_format($data['laba_kotor']['persediaan'] * -1) . ')'
                : number_format($data['laba_kotor']['persediaan']);
        $d_beban_pengolahan =
            $data['laba_kotor']['beban_pengolahan']['saldo'] < 0
                ? '(' . number_format($data['laba_kotor']['beban_pengolahan']['saldo'] * -1) . ')'
                : number_format($data['laba_kotor']['beban_pengolahan']['saldo']);
        $d_beban_angkut =
            $data['laba_kotor']['beban_angkut']['saldo'] < 0
                ? '(' . number_format($data['laba_kotor']['beban_angkut']['saldo'] * -1) . ')'
                : number_format($data['laba_kotor']['beban_angkut']['saldo']);
        $d_pot_pembelian =
            $data['laba_kotor']['pot_pembelian']['saldo'] < 0
                ? '(' . number_format($data['laba_kotor']['pot_pembelian']['saldo'] * -1) . ')'
                : number_format($data['laba_kotor']['pot_pembelian']['saldo']);
        $d_retur_pembelian = '(' . number_format($data['laba_kotor']['retur_pembelian']) . ')';

    @endphp
    <tr>
        <td></td>
        <td><b>Penjualan Bersih</b></td>
        <td style="text-align:right;"><b>{{ $d_penjualan_bersih }}</b></td>
    </tr>
    <tr>
        <td></td>
        <td>Persediaan Awal</td>
        <td style="text-align:right;">{{ $d_persediaan_awal }}</td>
    </tr>
    <tr>
        <td>1.1.03.01</td>
        <td>Persediaan</td>
        <td style="text-align:right;">{{ $d_persediaan }}</td>
    </tr>
    <tr>
        <td>{{ $data['laba_kotor']['beban_pengolahan']['kode'] }}</td>
        <td>{{ $data['laba_kotor']['beban_pengolahan']['nama'] }}
        </td>
        <td style="text-align:right;">{{ $d_beban_pengolahan }}</td>
    </tr>
    <tr>
        <td>{{ $data['laba_kotor']['beban_angkut']['kode'] }}</td>
        <td>{{ $data['laba_kotor']['beban_angkut']['nama'] }}</td>
        <td style="text-align:right;">{{ $d_beban_angkut }}</td>
    </tr>
    <tr>
        <td>{{ $data['laba_kotor']['pot_pembelian']['kode'] }}</td>
        <td>{{ $data['laba_kotor']['pot_pembelian']['nama'] }}</td>
        <td style="text-align:right;">{{ $d_pot_pembelian }}</td>
    </tr>
    <tr>
        <td>5.1.01.03</td>
        <td>Retur Pembelian</td>
        <td style="text-align:right;">{{ $d_retur_pembelian }}</td>
    </tr>
    @php
        $total_pembelian =
            $data['laba_kotor']['persediaan'] +
            $data['laba_kotor']['beban_pengolahan']['saldo'] +
            $data['laba_kotor']['beban_angkut']['saldo'] -
            $data['laba_kotor']['pot_pembelian']['saldo'];

        $total_persediaan = $data['laba_kotor']['persediaan_awal'] + $total_pembelian;
        $persediaan_akhir =
            $data['laba_kotor']['persediaan_awal'] +
            $data['laba_kotor']['persediaan_akhir']['saldo'] +
            $data['laba_kotor']['retur_pembelian'];
        $hpp = $total_persediaan - $persediaan_akhir;
        $laba_kotor = $penjualan_bersih - $hpp;

        $d_total_pembelian =
            $total_pembelian < 0 ? '(' . number_format($total_pembelian * -1) . ')' : number_format($total_pembelian);
        $d_total_persediaan =
            $total_persediaan < 0
                ? '(' . number_format($total_persediaan * -1) . ')'
                : number_format($total_persediaan);
        $d_persediaan_akhir =
            $persediaan_akhir < 0
                ? '(' . number_format($persediaan_akhir * -1) . ')'
                : number_format($persediaan_akhir);
        $d_hpp = $hpp < 0 ? '(' . number_format($hpp * -1) . ')' : number_format($hpp);
        $d_laba_kotor = $laba_kotor < 0 ? '(' . number_format($laba_kotor * -1) . ')' : number_format($laba_kotor);

    @endphp
    <tr>
        <td></td>
        <td><b>Total Pembelian</b></td>
        <td style="text-align:right;"><b>{{ $d_total_pembelian }}</b></td>
    </tr>
    <tr>
        <td></td>
        <td><b>Total Persediaan</b></td>
        <td style="text-align:right;"><b>{{ $d_total_persediaan }}</b></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <b>{{ $data['laba_kotor']['persediaan_akhir']['nama'] }} Akhir</b>
        </td>
        <td style="text-align:right;"><b>{{ $d_persediaan_akhir }}</b></td>
    </tr>
    <tr>
        <td></td>
        <td><b>Harga Pokok Penjualan</b></td>
        <td style="text-align:right;"><b>{{ $d_hpp }}</b></td>
    </tr>
    <tr>
        <td></td>
        <td><b>Laba Kotor</b></td>
        <td style="text-align:right;"><b>{{ $d_laba_kotor }}</b></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center;font-weight:bold;background: #eee;">PENDAPATAN LAIN-LAIN</td>
    </tr>
    @php
        $d_pendapatan_lain =
            $data['pendapatan_lain_lain']['saldo'] < 0
                ? '(' . number_format($data['pendapatan_lain_lain']['saldo'] * -1) . ')'
            : number_format($data['pendapatan_lain_lain']['saldo']); @endphp
    <tr>
        <td>{{ $data['pendapatan_lain_lain']['kode'] }}</td>
        <td>{{ $data['pendapatan_lain_lain']['nama'] }}</td>
        <td style="text-align:right;">{{ $d_pendapatan_lain }}</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center;font-weight:bold;background: #eee;">BEBAN OPERASIONAL</td>
    </tr>

    @php
    $t_saldo_beban_operasional = 0; @endphp
    @foreach ($data['beban_operasional'] as $ky)
        @php
            $saldo_beban_operasional =
                $ky->jenis_mutasi == 'debit' ? $ky->debit - $ky->kredit : $ky->kredit - $ky->debit;
            $d_saldo_beban_operasional =
                $saldo_beban_operasional < 0
                    ? '(' . number_format($saldo_beban_operasional * -1) . ')'
                    : number_format($saldo_beban_operasional);
        @endphp
        <tr>
            <td>{{ $ky->kode }}</td>
            <td>{{ $ky->nama }}</td>
            <td style="text-align:right;">{{ number_format($d_saldo_beban_operasional) }}</td>
        </tr>

        @php
        $t_saldo_beban_operasional += $saldo_beban_operasional; @endphp
    @endforeach
    @php
        $d_t_saldo_beban_operasional =
            $t_saldo_beban_operasional < 0
                ? '(' . number_format($t_saldo_beban_operasional * -1) . ')'
            : number_format($t_saldo_beban_operasional); @endphp
    <tr>
        <td></td>
        <td style="font-weight:bold;">Jumlah Beban Operasional</td>
        <td style="font-weight:bold;text-align:right;">{{ $d_t_saldo_beban_operasional }}</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center;font-weight:bold;background: #eee;">PENDAPATAN NON USAHA</td>
    </tr>
    @php
    $t_saldo_pendapatan_non_usaha = 0; @endphp
    @foreach ($data['pendapatan_non_usaha'] as $ky)
        @php
            $saldo_pendapatan_non_usaha =
                $ky->jenis_mutasi == 'debit' ? $ky->debit - $ky->kredit : $ky->kredit - $ky->debit;
            $d_saldo_pendapatan_non_usaha =
                $saldo_pendapatan_non_usaha < 0
                    ? '(' . number_format($saldo_pendapatan_non_usaha * -1) . ')'
                    : number_format($saldo_pendapatan_non_usaha);
        @endphp

        <tr>
            <td>{{ $ky->kode }}</td>
            <td>{{ $ky->nama }}</td>
            <td style="text-align:right;">{{ $d_saldo_pendapatan_non_usaha }}</td>
        </tr>

        @php
        $t_saldo_pendapatan_non_usaha += $saldo_pendapatan_non_usaha; @endphp
    @endforeach
    @php
        $d_t_saldo_pendapatan_non_usaha =
            $t_saldo_pendapatan_non_usaha < 0
                ? '(' . number_format($t_saldo_pendapatan_non_usaha * -1) . ')'
            : number_format($t_saldo_pendapatan_non_usaha); @endphp
    <tr>
        <td></td>
        <td style="font-weight:bold;">Jumlah Pendapatan Non Usaha</td>
        <td style="font-weight:bold;text-align:right;">{{ $d_t_saldo_pendapatan_non_usaha }}</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center;font-weight:bold;background: #eee;">BEBAN NON USAHA</td>
    </tr>
    @php
    $t_saldo_beban_non_usaha = 0; @endphp
    @foreach ($data['beban_non_usaha'] as $ky)
        @php
            $saldo_beban_non_usaha = $ky->jenis_mutasi == 'debit' ? $ky->debit - $ky->kredit : $ky->kredit - $ky->debit;
            $d_saldo_beban_non_usaha =
                $saldo_beban_non_usaha < 0
                    ? '(' . number_format($saldo_beban_non_usaha * -1) . ')'
                    : number_format($saldo_beban_non_usaha);
        @endphp
        <tr>
            <td>{{ $ky->kode }}</td>
            <td>{{ $ky->nama }}</td>
            <td style="text-align:right;">{{ number_format($saldo_beban_non_usaha) }}</td>
        </tr>
        @php
        $t_saldo_beban_non_usaha += $saldo_beban_non_usaha; @endphp
    @endforeach
    @php
        $d_t_saldo_beban_non_usaha =
            $t_saldo_beban_non_usaha < 0
                ? '(' . number_format($t_saldo_beban_non_usaha * -1) . ')'
                : number_format($t_saldo_beban_non_usaha);
        $laba_rugi_sebelum_pajak =
            $laba_kotor -
            $t_saldo_beban_operasional +
            $t_saldo_pendapatan_non_usaha +
            $data['pendapatan_lain_lain']['saldo'] -
            $t_saldo_beban_non_usaha;
        $d_laba_rugi_sebelum_pajak =
            $laba_rugi_sebelum_pajak < 0
                ? '(' . number_format($laba_rugi_sebelum_pajak * -1) . ')'
                : number_format($laba_rugi_sebelum_pajak);
    @endphp
    <tr>
        <td></td>
        <td style="font-weight:bold;">Jumlah Beban Non Usaha</td>
        <td style="font-weight:bold;text-align:right;">{{ $d_t_saldo_beban_non_usaha }}</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight:bold;">Laba Rugi Sebelum Pajak</td>
        <td style="font-weight:bold;text-align:right;">{{ $d_laba_rugi_sebelum_pajak }}</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center;font-weight:bold;background: #eee;">BEBAN PAJAK</td>
    </tr>
    @php
    $t_saldo_beban_pajak = 0; @endphp
    @foreach ($data['beban_pajak'] as $ky)
        @php
            $saldo_beban_pajak = $ky->jenis_mutasi == 'debit' ? $ky->debit - $ky->kredit : $ky->kredit - $ky->debit;
            $d_saldo_beban_pajak =
                $saldo_beban_pajak < 0
                    ? '(' . number_format($saldo_beban_pajak * -1) . ')'
                    : number_format($saldo_beban_pajak);
        @endphp
        <tr>
            <td>{{ $ky->kode }}</td>
            <td>{{ $ky->nama }}</td>
            <td style="text-align:right;">{{ $d_saldo_beban_pajak }}</td>
        </tr>

        @php
        $t_saldo_beban_pajak += $saldo_beban_pajak; @endphp
    @endforeach
    @php
        $laba_rugi = $laba_rugi_sebelum_pajak - $t_saldo_beban_pajak;
        $d_t_saldo_beban_pajak =
            $t_saldo_beban_pajak < 0
                ? '(' . number_format($t_saldo_beban_pajak * -1) . ')'
                : number_format($t_saldo_beban_pajak);
        $d_laba_rugi = $laba_rugi < 0 ? '(' . number_format($laba_rugi * -1) . ')' : number_format($laba_rugi);
    @endphp
    <tr>
        <td></td>
        <td style="font-weight:bold;">Jumlah Beban Pajak</td>
        <td style="font-weight:bold;text-align:right;">{{ $d_t_saldo_beban_pajak }}</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight:bold;">Laba Rugi</td>
        <td style="font-weight:bold;text-align:right;">{{ $d_laba_rugi }}</td>
    </tr>
</table>
