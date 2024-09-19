<table>
	<tr>
		<th>No</th>
		<th>Kode</th>
		<th>Nama</th>
		<th>Debit</th>
		<th>Kredit</th>
	</tr>
	<?php $no=1; ?>
	@foreach($rek as $r=>$k)
	<tr>
		<td>{{$no++}}</td>
		<td>{{$k['kode']}}</td>
		<td>{{$k['nama']}}</td>
		<td></td>
		<td></td>
	</tr>
	@endforeach
</table>