@php
    $rekening = $data['rekening'];
    $persediaan_akhir = 0;
    $penjualan_bersih = 0;
    $persediaan_awal = 0;
    $return = 0;
@endphp

<style>
    td {
        padding-left: 4px;
        padding-right: 4px;
    }
</style>

<table class="table table-striped" style="width: 100%;border-collapse: collapse;" {!! Request::get('print') == 'yes' ? 'border="1"' : '' !!}>
    @foreach ($rekening as $rek)
        @php
            $jumlah_saldo = 0;
        @endphp
        <tr>
            <td colspan="3" style="font-weight:bold;background: #eee;">
                {{ strtoupper($rek['nama']) }}
            </td>
        </tr>

        @foreach ($rek['kode'] as $kode)
            @if ($kode['kode'] == '1.1.03.01')
                <tr>
                    <td width="10%"></td>
                    <td>
                        <b>Penjualan Bersih</b>
                    </td>
                    <td width="15%" style="text-align:right;">
                        <b>{{ number_format($jumlah_saldo) }}</b>
                    </td>
                </tr>

                <tr>
                    <td width="10%"></td>
                    <td>Persediaan Awal</td>
                    <td width="15%" style="text-align:right;">
                        {{ number_format($kode['saldo_tahun_lalu'] + $kode['saldo_bulan_lalu']) }}
                    </td>
                </tr>

                @php
                    $penjualan_bersih = $jumlah_saldo;
                    $persediaan_awal = $kode['saldo_tahun_lalu'] + $kode['saldo_bulan_lalu'];
                    $persediaan_akhir = $kode['saldo'];
                    $jumlah_saldo = 0;
                @endphp
            @endif
            <tr>
                <td width="10%" align="center">{{ $kode['kode'] }}</td>
                <td>{{ $kode['nama'] }}</td>
                <td width="15%" style="text-align:right;">{{ number_format($kode['saldo_bulan_ini']) }}</td>
            </tr>

            @php
                if ($kode['kode'] == '5.1.01.03') {
                    $return = $kode['saldo_bulan_ini'];
                    $kode['saldo_bulan_ini'] = 0;
                }

                if ($kode['kode'] == '5.1.01.02') {
                    $jumlah_saldo -= $kode['saldo_bulan_ini'];
                } else {
                    $jumlah_saldo += $kode['saldo_bulan_ini'];
                }
            @endphp
        @endforeach
        @if ($rek['total'] != '')
            <tr>
                <td width="10%"></td>
                <td>
                    <b>{{ $rek['total'] }}</b>
                </td>
                <td width="15%" style="text-align:right;">
                    <b>{{ number_format($jumlah_saldo) }}</b>
                </td>
            </tr>
        @endif

        @if ($loop->iteration == '1')
            <tr>
                <td width="10%"></td>
                <td>
                    <b>Total Persediaan</b>
                </td>
                <td width="15%" style="text-align:right;">
                    <b>{{ number_format($persediaan_awal + $jumlah_saldo) }}</b>
                </td>
            </tr>
            <tr>
                <td width="10%"></td>
                <td>
                    <b>Persediaan Akhir</b>
                </td>
                <td width="15%" style="text-align:right;">
                    <b>{{ number_format($persediaan_awal + $persediaan_akhir + $return) }}</b>
                </td>
            </tr>
            <tr>
                <td width="10%"></td>
                <td>
                    <b>Harga Pokok Penjualan</b>
                </td>
                <td width="15%" style="text-align:right;">
                    <b>
                        {{ number_format($persediaan_awal + $jumlah_saldo - ($persediaan_awal + $persediaan_akhir + $return)) }}
                    </b>
                </td>
            </tr>
            <tr>
                <td width="10%"></td>
                <td>
                    <b>Laba Kotor</b>
                </td>
                <td width="15%" style="text-align:right;">
                    <b>
                        {{ number_format($penjualan_bersih - ($persediaan_awal + $jumlah_saldo - ($persediaan_awal + $persediaan_akhir + $return))) }}
                    </b>
                </td>
            </tr>
        @endif
    @endforeach
</table>
