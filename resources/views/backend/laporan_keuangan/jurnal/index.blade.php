@extends('backend.layout.main') 
@section('content')

<section class="forms">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mt-2">
                <h3 class="text-center">Jurnal</h3>
            </div>
            @include('backend.laporan_keuangan.filter')
            <div class="row" style="margin:10px;">
            	<div class="col-md-12">
            		@if($tahun !='' && $bulan !='')
            		@include('backend.laporan_keuangan.jurnal.table')
					@else
					<center>- Pilih Bulan dan Tahun Dahulu -</center>
					@endif
            	</div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script type="text/javascript">
$(".daterangepicker-field").daterangepicker({
  callback: function(startDate, endDate, period){
    var start_date = startDate.format('YYYY-MM-DD');
    var end_date = endDate.format('YYYY-MM-DD');
    var title = start_date + ' to ' + end_date;
    $(this).val(title);
    $('input[name="start_date"]').val(start_date);
    $('input[name="end_date"]').val(end_date);
  }
});

function doFilter(param=null){
	var is_param = '';
	var url ='{{url("laporan_keuangan/jurnal?warehouse_id")}}='+$('#warehouse_id').val()+'&tahun='+$('#tahun').val()+'&bulan='+$('#bulan').val()+'&hari='+$('#hari').val();

	if(param == 'print'){
		is_param = '&print=yes';

		 var x = screen.width/2 - 1000/2;
         var y = screen.height/2 - 500/2;

         window.open(url+is_param,'Cetak Dokumen',"width=1000, height=500, left="+x+", top="+y+"");

         return;
	}

	if(param == 'xls'){
		is_param = '&xls=yes';
	}
	
	location.href = url+is_param;
}
</script>
@endpush
