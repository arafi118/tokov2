<!DOCTYPE html>
<html>

<head>
    <title>Laporan Jurnal</title>
    <style type="text/css">
        body {
            font-size: 12px;

        }

        @page {

            footer: page-footer;
        }
    </style>
</head>

<body>
    @if ($header == 'yes')
        {!! headerDoc($logo) !!}
    @endif
    <table style="width:100%;">
        <tr>
            <td colspan="7" style="text-align:center;font-weight: bold;">
                <h3 style="text-align: center;">JURNAL</h3>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align:center;font-weight: bold;">
                <h4 style="text-align:center;">{{ $data['sub_judul'] }}</h4>
            </td>
        </tr>
    </table>
    @include('backend.laporan_keuangan.jurnal.table')
</body>

</html>
