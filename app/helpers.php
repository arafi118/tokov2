<?php
http://minggirmart.salepro.test/images/biller/PTWarungSembakoIndonesia.jpeg
function headerDoc($logo)
{
		$pos_setting = \DB::connection(env('TENANT_DB_CONNECTION'))
											->table('pos_setting')
											->selectRaw('biller_id')
											->first();

    $biller 		 = \DB::connection(env('TENANT_DB_CONNECTION'))->table('billers')
    																								    ->selectRaw('*')
    																								    ->where('id',$pos_setting->biller_id)
    																								    ->first();
    

    if($logo == 'yes'){
    	 $logo = '<tr>
              <td align="left" rowspan="3" width="20%"><img src='.url('public/images/biller', $biller->image).' width="100"></td>
              <td align="left" valign="top">
               
                <h1>'.$biller->name.'</h1>

              </td>
            </tr>';
    }else{
    	$logo = '<tr>
              
              <td align="left" valign="top">
               
                <h1>'.$biller->name.'</h1>
              </td>
            </tr>';
    }
   
	$header = '
          <table border="0" width="100%">
            '.$logo.'
            <tr>
            	<td><p style="font-size:12px;">'
            		.$biller->address.', '.$biller->city.', '.$biller->state.', '.$biller->postal_code.
            	'</p></td>
            </tr>
             <tr>
            	<td><p style="font-size:12px;"> Email : '.$biller->email.' Telp :  '.$biller->phone_number.'</p></td>
            </tr>
            <tr>
              <td colspan="2" style="border-bottom:1px solid #000"></td>
            </tr>
          </table>
       ';

    return $header;
}

function footerDoc($warehouse){
	return  '<hr><i><small>'.strtoupper('warehouse : '.$warehouse).'</small></i>';
}


function bulan($bln)
{
	$ex = explode(' ', $bln);

	$bln = count($ex) == 3 ? (string)$ex[1] : (string) $bln;
	$fbln = '';
	switch ($bln)
	{
		case "01":
			$fbln = "Januari";
			break;
		case "02":
			$fbln = "Februari";
			break;
		case "03":
			$fbln = "Maret";
			break;
		case "04":
			$fbln = "April";
			break;
		case "05":
			$fbln = "Mei";
			break;
		case "06":
			$fbln = "Juni";
			break;
		case "07":
			$fbln = "Juli";
			break;
		case "08":
			$fbln = "Agustus";
			break;
		case "09":
			$fbln = "September";
			break;
		case "10":
			$fbln = "Oktober";
			break;
		case "11":
			$fbln = "Nopember";
			break;
		case "12":
			$fbln = "Desember";
			break;
	}

	return count($ex) == 3 ? $ex[0].' '.$fbln.' '.$ex[2] : $fbln;	
}