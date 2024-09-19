<table class="table table-striped" style="width: 100%;border-collapse: collapse;" {!! Request::get('print') == 'yes' ? 'border="1"' : '' !!}>
    <thead>
        @if (Request::get('xls') == 'yes')
            <tr>
                <th style="font-weight: bold;" width="5">No</th>
                <th style="font-weight: bold;" width="10">Tanggal</th>
                <th style="font-weight: bold;" width="18">Ref ID</th>
                <th style="font-weight: bold;" width="10">Kd. Rek</th>
                <th style="font-weight: bold;" width="50">Keterangan</th>
                <th style="font-weight: bold;" width="15">Debit</th>
                <th style="font-weight: bold;" width="15">Kredit</th>
                <th style="font-weight: bold;" width="15">Ins</th>
            </tr>
        @else
            <tr style="background:#eee;">
                <th width="5%" height="30">No</th>
                <th width="10%">Tanggal</th>
                <th width="9%">Ref ID</th>
                <th width="9%">Kd. Rek</th>
                <th width="38%">Keterangan</th>
                <th width="12%">Debit</th>
                <th width="12%">Kredit</th>
                <th width="5%">Ins</th>
            </tr>
        @endif
    </thead>
    <tbody>
        @php
            $no = 1;
            $ttl_d = 0;
            $ttl_k = 0;
        @endphp
        @foreach ($data['transaksi'] as $jurnal)
            @php
                $ni = 0;
            @endphp

            @foreach ($jurnal->details as $dt)
                <tr>
                    <td style="text-align: center;">{{ $ni == 0 ? $no . '.' : '' }}</td>
                    <td style="text-align: center;">
                        {{ $ni == 0 ? \Carbon\Carbon::parse($jurnal->tgl_transaksi)->format('d/m/Y') : '' }}
                    </td>
                    <td style="text-align: center;">{{ $ni == 0 ? strtoupper($jurnal->nomor_transaksi) : '' }}</td>
                    <td style="text-align: center;">{{ $dt->debit_kode }}</td>
                    <td>{{ $dt->rek_debit->nama }}</td>
                    <td style="text-align: right;">{{ number_format($dt->debit_nominal) }}</td>
                    <td></td>
                    <td style="text-align:center;">{{ $jurnal->pic != null ? $jurnal->pic->initial : '' }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;">{{ $dt->kredit_kode }}</td>
                    <td>{{ $dt->rek_kredit->nama }}</td>
                    <td></td>
                    <td style="text-align: right;">{{ number_format($dt->kredit_nominal) }}</td>
                    <td style="text-align:center;">{{ $jurnal->pic != null ? $jurnal->pic->initial : '' }}</td>
                </tr>

                @php
                    $ni++;
                    $ttl_d += $dt->debit_nominal;
                    $ttl_k += $dt->kredit_nominal;
                @endphp
            @endforeach

            @php
                $no++;
            @endphp
        @endforeach
        <tr>
            <td colspan="5" height="35"></td>
            <td style="text-align: right;font-weight: bold;">{{ number_format($ttl_d) }}</td>
            <td style="text-align: right;font-weight: bold;">{{ number_format($ttl_k) }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
