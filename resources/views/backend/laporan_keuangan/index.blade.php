@extends('backend.layout.main')
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h3 class="text-center">{{ trans('file.Financial Statements') }}</h3>
                </div>
                <div class="card-body">
                    @include('backend.laporan_keuangan.filter')
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("ul#account").siblings('a').attr('aria-expanded', 'true');
            $("ul#account").addClass("show");
            $("ul#account #laporan-keuangan-menu").addClass("active");

            $('#jenis_buku_id').val('');
            $('#jenis_buku_id').attr('disabled', 'disabled');
            $('#hari').val('');
            $('#hari').attr('disabled', 'disabled');
            $('#jb').hide();
            $('#invent').hide();

            $('#jenis_laporan').on('change', function() {
                if ($('#jenis_laporan').val() == 'buku_besar') {
                    $('#jenis_buku_id').val('');
                    $('#jenis_buku_id').removeAttr('disabled');
                    $('#jb').show();
                } else {
                    $('#jenis_buku_id').val('');
                    $('#jenis_buku_id').attr('disabled', 'disabled');
                    $('#jb').hide();
                }

                if ($('#jenis_laporan').val() == 'inventaris') {

                    $('#invent').show();
                } else {

                    $('#invent').hide();
                }

                if ($('#jenis_laporan').val() == 'buku_besar' || $('#jenis_laporan').val() == 'jurnal') {
                    $('#hari').val('');
                    $('#hari').removeAttr('disabled');
                } else {
                    $('#hari').val('');
                    $('#hari').attr('disabled', 'disabled');
                }

            });
        });

        function doFilter(param = null) {
            var is_param = '';
            var url = '';
            var jenis_laporan = $('#jenis_laporan').val();


            if (jenis_laporan == '') {
                swal('Peringatan', 'Pilih Jenis Laporan dahulu', 'error');

                return;
            }

            if ($('#warehouse_id').val() == '') {
                swal('Peringatan', 'Pilih Warehouse dahulu', 'error');

                return;
            }

            if ($('#tahun').val() == '') {
                swal('Peringatan', 'Pilih Tahun dahulu', 'error');

                return;
            }

            if ($('#bulan').val() == '') {
                swal('Peringatan', 'Pilih Bulan dahulu', 'error');

                return;
            }

            if (jenis_laporan == 'jurnal') {
                url = "{{ url('laporan_keuangan/jurnal?warehouse_id') }}=" + $("#warehouse_id").val() + "&tahun=" + $(
                    "#tahun").val() + "&bulan=" + $("#bulan").val() + "&hari=" + $("#hari").val();
            } else if (jenis_laporan == 'buku_besar') {

                if ($('#jenis_buku_id').val() == '') {
                    swal('Peringatan', 'Pilih Jenis Buku dahulu', 'error');

                    return;
                }

                url = "{{ url('laporan_keuangan/buku_besar?warehouse_id') }}=" + $("#warehouse_id").val() + "&tahun=" + $(
                        "#tahun").val() + "&bulan=" + $("#bulan").val() + "&hari=" + $("#hari").val() + "&jenis_buku_id=" +
                    $("#jenis_buku_id").val();
            } else if (jenis_laporan == "neraca") {
                url = "{{ url('laporan_keuangan/neraca?warehouse_id') }}=" + $("#warehouse_id").val() + "&tahun=" + $(
                    "#tahun").val() + "&bulan=" + $("#bulan").val();
            } else if (jenis_laporan == "neraca_saldo") {
                url = "{{ url('laporan_keuangan/neraca_saldo?warehouse_id') }}=" + $("#warehouse_id").val() + "&tahun=" + $(
                    "#tahun").val() + "&bulan=" + $("#bulan").val();
            } else if (jenis_laporan == "rugi_laba") {
                url = "{{ url('laporan_keuangan/rugi_laba?warehouse_id') }}=" + $("#warehouse_id").val() + "&tahun=" + $(
                    "#tahun").val() + "&bulan=" + $("#bulan").val();
            } else if (jenis_laporan == "arus_kas") {
                url = "{{ url('laporan_keuangan/arus_kas?warehouse_id') }}=" + $("#warehouse_id").val() + "&tahun=" + $(
                    "#tahun").val() + "&bulan=" + $("#bulan").val();
            } else if (jenis_laporan == "inventaris") {
                url = "{{ url('laporan_keuangan/inventaris?warehouse_id') }}=" + $("#warehouse_id").val() + "&tahun=" + $(
                        "#tahun").val() + "&bulan=" + $("#bulan").val() + "&hari=" + $("#hari").val() + "&inventaris_id=" +
                    $("#inventaris_id").val();
            }

            if (param == 'print') {
                is_param = '&print=yes';

                var x = screen.width / 2 - 1000 / 2;
                var y = screen.height / 2 - 500 / 2;

                window.open(url + is_param, 'Cetak Dokumen', "width=1000, height=500, left=" + x + ", top=" + y + "");

                return;
            }

            if (param == 'xls') {
                is_param = '&xls=yes';
            }

            location.href = url + is_param;
        }
    </script>
@endpush
