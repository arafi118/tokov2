@extends('backend.layout.administrator')
@section('content')
<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="activity"></i></div>
                        Tambah Tenant
                    </h1>
                    <div class="page-header-subtitle"></div>
                </div>
                <div class="col-12 col-xl-auto mt-4">
                   
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-xl px-4 mt-n10">
     <div class="row">
        <div class="col-xxl-4 col-xl-12 mb-4">
            <div class="card">
                <div class="card-header">
                     <div class="row">
                        <div class="col-md-12">
                             <a href="{{route('admin.tenant')}}"><button type="button" class="btn btn-sm btn-primary"><i class="fas fa-arrow-left"></i>&nbsp;Kembali</button></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('admin.tenant.store')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label for="exampleFormControlInput1">Nama Tenant</label>
                            <input class="form-control @error('nama_tenant') is-invalid @enderror" id="nama_tenant" name="nama_tenant" value="{{isset($tenant) ? $tenant->nama_tenant : old('nama_tenant')}}" type="text" placeholder="Harus Diisi">
                            @error('nama_tenant')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-danger" style="float:right; margin-left: 5px;" type="submit">Submit</button>
                            <a href="{{route('admin.tenant')}}"><button class="btn btn-success" style="float:right;" type="button">Batal</button></a>
                            
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        <?php if(session('status')): ?>
          
            toastError('{{session("status")}}');;
            
        <?php endif ?>
    });
</script>
@endsection