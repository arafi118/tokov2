<table class="table table-striped" style="width: 100%; border-collapse: collapse;" @if(Request::get('print') == 'yes') border="1" @endif>
    <thead>
        @if(Request::get('xls') == 'yes')
        <tr style="background:#eee;">
            <th rowspan="2" width="5" style="font-weight:bold;">No</th>
            <th rowspan="2" width="10" style="font-weight:bold;">Tanggal Beli</th>
            <th rowspan="2" width="18" style="font-weight:bold;">Nama Barang</th>
            <th rowspan="2" width="5" style="font-weight:bold;">ID</th>
            <th rowspan="2" width="15" style="font-weight:bold;">Kondisi</th>
            <th rowspan="2" width="15" style="font-weight:bold;">Unit</th>
            <th rowspan="2" width="15" style="font-weight:bold;">Harga Satuan</th>
            <th rowspan="2" width="15" style="font-weight:bold;">Harga Perolehan</th>
            <th rowspan="2" width="10" style="font-weight:bold;">Umur Eko.</th>
            <th rowspan="2" width="10" style="font-weight:bold;">Satuan Susut</th>
            <th  width="10" colspan="2" style="font-weight:bold;">Tahun Ini</th>
            <th  width="10" colspan="2" style="font-weight:bold;">SD Tahun Ini</th>
            <th  rowspan="2" width="15" style="font-weight:bold;">Nilai Biaya</th>
        </tr>
        <tr>
            <th style="font-weight:bold;">Umur</th>
            <th style="font-weight:bold;">Biaya</th>
            <th style="font-weight:bold;">Umur</th>
            <th style="font-weight:bold;">Biaya</th>
        </tr>
        @else
        <tr style="background:#eee;">
            <th rowspan="2" width="5" >No</th>
            <th rowspan="2" width="10" >Tanggal Beli</th>
            <th rowspan="2" width="18" >Nama Barang</th>
            <th rowspan="2" width="5" >ID</th>
            <th rowspan="2" width="15" >Kondisi</th>
            <th rowspan="2" width="15" >Unit</th>
            <th rowspan="2" width="15" >Harga Satuan</th>
            <th rowspan="2" width="15" >Harga Perolehan</th>
            <th rowspan="2" width="10" >Umur Eko.</th>
            <th rowspan="2" width="10" >Satuan Susut</th>
            <th  width="10" colspan="2" >Tahun Ini</th>
            <th  width="10" colspan="2" >SD Tahun Ini</th>
            <th  rowspan="2" width="15" >Nilai Biaya</th>
        </tr>
        <tr>
            <th width="5">Umur</th>
            <th width="5">Biaya</th>
            <th width="5">Umur</th>
            <th width="5">Biaya</th>
        </tr>
        @endif
    </thead>
    <tbody>
        <?php $no=1;$ttl_unit =0; $ttl_perolehan = 0;?>
        @foreach($inventaris as $inv)

        <?php 
       
        $data = $inv->queryInventaris($bulan,$tahun,$hari,$warehouse_id,$inv->id); ?>
        @if($data->count() > 0)
        <tr>
            <td colspan="15">{{$no++}}.{{$inv->nama}}</td>
        </tr>
            <?php $ni = 1; ?>
             @foreach($data as $dat)
             <tr>
                 <td style="text-align:center;">{{$ni}}</td>
                 <td style="text-align:center;">{{\Carbon\Carbon::parse($dat->tgl_beli)->format('d/m/Y')}}</td>
                 <td>{{$dat->nama_barang}}</td>
                 <td style="text-align:center;">{{$ni}}</td>
                 <td style="text-align:center;">{{ucfirst($dat->status)}}</td>
                 <td style="text-align:center;">{{$dat->unit}}</td>
                 <td style="text-align:right;">{{number_format($dat->harga_satuan)}}</td>
                 <td style="text-align:right;">{{number_format($dat->unit * $dat->harga_satuan)}}</td>
                 <td colspan="6"></td>
                 <td style="text-align:right;">{{number_format($dat->unit * $dat->harga_satuan)}}</td>
             </tr>
             <?php $ni++; $ttl_unit += $dat->unit; $ttl_perolehan += ($dat->unit * $dat->harga_satuan); ?>
             @endforeach
        @endif  
        
        @endforeach
        <tr>
            <td colspan="5">Jumlah Daftar ATI (Hapus, Hilang, Jual) sd Tahun {{$tahun}}</td>
            <td></td>
            <td></td>
            <td ></td>
            <td colspan="6"></td>
            <td style="text-align:right;"></td>
        </tr>
        <tr>
            <td colspan="5">Jumlah</td>
            <td style="text-align:center;">{{$ttl_unit}}</td>
            <td></td>
            <td style="text-align:right;">{{number_format($ttl_perolehan)}}</td>
            <td colspan="6"></td>
            <td style="text-align:right;">{{number_format($ttl_perolehan)}}</td>
        </tr>
    </tbody>
</table>