<div class="row">
	<div class="col-md-12">
		<table class="table table-striped">
			<tr>
				<td>Warehouse</td>
				<td>{{$jurnal->warehouse}}</td>
			</tr>
			<tr>
				<td>Transaksi</td>
				<td>{{$jurnal->induk}}</td>
			</tr>
			<tr>
				<td>Tgl Transaksi</td>
				<td>{{$jurnal->tgl_transaksi}}</td>
			</tr>
			<tr>
				<td>Nomor Transaksi</td>
				<td>{{$jurnal->nomor_transaksi}}</td>
			</tr>
			<tr>
				<td>Memo</td>
				<td>{{$jurnal->memo}}</td>
			</tr>
		</table>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Jenis Transaksi</th>
					<th>Debit</th>
					<th>Kedit</th>
					<th>Nominal</th>
					<th>Deskripsi</th>
				</tr>
			</thead>
			<tbody>
				@foreach($details as $det)
				<tr>
					<td>{{$det->jenis_transaksi}}</td>
					<td>{{$det->debit_kode}} - {{$det->debit_nama}}</td>
					<td>{{$det->kredit_kode}} - {{$det->kredit_nama}}</td>
					<td style="text-align:right;">{{$det->debit_nominal}}</td>
					<td>{{$det->deskripsi}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>