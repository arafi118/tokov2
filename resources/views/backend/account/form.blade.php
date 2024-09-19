@extends('backend.layout.main') 
@section('content')

<section>
        <div class="container-fluid">
        	<div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>Form Rekening</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>The field labels marked with * are required input fields.</small></p>
                        {!! Form::open(['route' => ['accounts.store'], 'method' => 'post']) !!}
                        @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ucwords(trans('file.parent'))}}</label>
                                        <select class="selectpicker form-control" name="parent_id">
                                            <option value="">--Top Parent--</option>
                                         @foreach($drop as $key=>$val)
                                           <option value="{{$val['id']}}"
                                           <?php if(isset($akun)): ?>
                                                <?php if($val['id'] == $akun->parent_id): ?>
                                                    selected="selected"
                                                <?php endif ?>
                                           <?php elseif(!isset($akun)): ?>
                                             <?php if($val['id'] == old('parent_id')): ?>
                                                    selected="selected"
                                                <?php endif ?>
                                           <?php endif ?>
                                           >
                                           <?php
                                                if(isset($val['depth'])){
                                                    $depth = str_repeat("-", $val['depth']);
                                                }else{
                                                    $depth = "";
                                                }
                                           ?>
                                         {{$depth."".$val['kode'].' - '.$val['nama']}}
                                          </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.Account')}} No *</label>
                                        <input type="text" name="account_no" value="{{isset($akun) ? $akun->kode : ''}}" required class="form-control">
                                        <input type="hidden" name="account_id" value="{{isset($akun) ? $akun->id : ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('file.name')}} *</label>
                                        <input type="text" name="name"  value="{{isset($akun) ? $akun->nama : ''}}" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ucwords(trans('file.Mutation Type'))}}</label>
                                        <select class=" form-control" name="jenis_mutasi">
                                            <option value="debit" @if(isset($akun)) @if($akun->jenis_mutasi == 'debit') selected="" @endif @endif>Debit</option>
                                            <option value="kredit" @if(isset($akun)) @if($akun->jenis_mutasi == 'kredit') selected="" @endif @endif>Kredit</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <p class="italic"><small>Khusus untuk rekening bank.</small></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No Rekening Bank</label>
                                        <input type="text" name="no_rek_bank"  value="{{isset($akun) ? $akun->no_rek_bank : ''}}" class="form-control">
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Atas Nama Rekening Bank</label>
                                        <input type="text" name="atas_nama_rek"  value="{{isset($akun) ? $akun->atas_nama_rek : ''}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="{{route('accounts.index')}}" class="btn btn-danger">{{trans('file.Cancel')}}</a>
                                        <button type="submit" class="btn btn-primary">{{trans('file.update')}}</button>
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