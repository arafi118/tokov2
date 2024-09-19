<form method="post" action="{{route('sale.add-payment-cicilan')}}">
@csrf
<input type="hidden" name="sale_id" value="{{$sale->id}}">
<div class="row">
	 <div class="col-md-3">
        <label>{{trans('file.Paid By')}}</label>
        <select onchange="choosePaymentMethod($(this).val())" name="cicilan_paid_by_id" id="cicilan_paid_by_id" class="form-control">
            <option value="1">Cash</option>
            <option value="5">Debit Card</option>
        </select>
    </div>
    <div class="col-md-9" id="cicilan_debit_card">
        <label>Transfer / Debit Card</label>
        <select class="form-control" name="cicilan_no_rek_bank">
        @foreach($rekening as $rek)
            
            <option value="{{$rek->id}}">{{$rek->nama}} - {{$rek->no_rek_bank}} - {{$rek->atas_nama_rek}}</option>
          
        @endforeach
        </select>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <label>{{trans('file.Payment Note')}}</label>
            <textarea rows="3" class="form-control" name="cicilan_payment_note"></textarea>
        </div>
    </div>
</div>
<div class="row" style="margin-top:10px;">
	<div class="col-md-6">
		<div class="form-group">
			<label>Total Tagihan</label>
			<input type="text" style="text-align:right;" value="{{$total_tagihan - $total_terbayar}}" id="total_tagihan" name="total_tagihan" class="form-control" readonly>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label>Total Bayar</label>
			<input type="text" style="text-align:right;" onkeyup="hitungBayar($(this).val())" value="0" id="total_bayar" name="total_bayar" class="form-control" >
		</div>
	</div>
</div>
<div class="row" style="margin-top:10px;">
	<div class="col-md-6"></div>
	<div class="col-md-6">
		<button class="btn btn-primary" style="width:100%;">Bayar</button></td>
	</div>
</div>
</form>