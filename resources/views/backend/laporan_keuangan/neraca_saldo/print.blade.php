<!DOCTYPE html>
<html>
<head>
  <title>Neraca Saldo</title>
  <style type="text/css">
    body{
      font-size: 12px;
      
    }
    /*header { position: fixed; top: -60px; left: 0px; right: 0px;  height: 50px; }*/
      /*footer { position: fixed; bottom: -50px; left: 0px; right: 0px;  height: 50px; }*/
      @page {
    
       footer: page-footer;
    }
  </style>
</head>
<body>
  @if($header == 'yes')
  {!! headerDoc($logo) !!} 
  @endif
  <table style="width:100%;">
    <tr>
      <td colspan="3" style="text-align:center;font-weight: bold;">
        <h3 style="text-align: center;">NERACA SALDO</h3>
      </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center;font-weight: bold;">
           <h4 style="text-align:center;">{{$data['sub_judul']}}</h4>
        </td>
    </tr>
  </table>
  @include('backend.laporan_keuangan.neraca_saldo.table')
</body>
</html>