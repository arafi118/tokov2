@extends('backend.layout.main') 
@section('content')
@if(session()->has('error'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('error') }}</div>
@endif
<section>
    <div class="container-fluid">
    	<div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>Form Impor Saldo Awal</h4>
                    </div>
                    <div class="card-body">
                    {!! Form::open(['route' => ['accounts.impor_saldo_awal'], 'method' => 'post','enctype'=>'multipart/form-data']) !!}
                    @csrf
                        <div class="row">
                             <div class="col-md-6">
                               
                                    <label class="form-label" for="tahun">Warehouse</label>
                                    <select required name="warehouse_id" id="warehouse_id" class="selectpicker form-control" data-live-search="true" title="Pilih warehouse...">
                                        @foreach($lims_warehouse_list as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                
                             </div>
                             <div class="col-md-2">
                                
                                    <label class="form-label" for="tahun">Tahun</label>
                                    <select class="form-control" name="tahun" id="tahun" required>
                                        <option value="">---</option>
                                        @for ($i = 2020; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <small class="text-danger" id="msg_tahun"></small>
                                
                            </div>
                            <div class="col-md-4">
                                    <label class="form-label" for="bulan">Bulan</label>
                                    <select class="form-control" name="bulan" id="bulan" required>
                                        <option value="">---</option>
                                        <option value="01">01. JANUARI</option>
                                        <option value="02">02. FEBRUARI</option>
                                        <option value="03">03. MARET</option>
                                        <option value="04">04. APRIL</option>
                                        <option value="05">05. MEI</option>
                                        <option value="06">06. JUNI</option>
                                        <option value="07">07. JULI</option>
                                        <option value="08">08. AGUSTUS</option>
                                        <option value="09">09. SEPTEMBER</option>
                                        <option value="10">10. OKTOBER</option>
                                        <option value="11">11. NOVEMBER</option>
                                        <option value="12">12. DESEMBER</option>
                                    </select>
                                    <small class="text-danger" id="msg_bulan"></small>
                                
                            </div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Format Dokumen</label><br>
                                    <a href="{{route('accounts.format_saldo_awal')}}"><i><small>Download</small></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dokumen Saldo Awal</label><br>
                                    <input required type="file" name="file_saldo" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis Periode Saldo</label>
                                    <select class="form-control" name="jenis_periode_saldo" id="jenis_periode_saldo" required>
                                        <option value="bulan_lalu">Bulan Lalu</option>
                                        <option value="awal_tahun">Awal Tahun</option>
                                    </select>
                                </div>
                            </div>    
                        </div>    
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{route('accounts.index')}}" class="btn btn-danger">{{trans('file.Cancel')}}</a>
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </div>
                            </div>
                        </div>
                    {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection