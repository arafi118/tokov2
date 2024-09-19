@extends('backend.layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header mt-2">
                <h3 class="text-center">Tutup Buku</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('tutup_buku.submit')}}">
                @csrf
                <div class="row">
                   <div class="col-md-3">
                        <div class="my-2">
                            <label class="form-label" for="tahun">Warehouse</label>
                            <select required name="warehouse_id" id="warehouse_id" class="selectpicker form-control" data-live-search="true" title="Pilih warehouse...">
                                @foreach($lims_warehouse_list as $warehouse)
                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="my-2">
                            <label class="form-label" for="tahun">Tahunan</label>
                            <select class="form-control" name="tahun" id="tahun">
                                <option value="">---</option>
                                @for ($i = 2020; $i <= date('Y'); $i++)
                                    <option {{ $i == date('Y') ? 'selected' : '' }} value="{{ $i }}">
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <small class="text-danger" id="msg_tahun"></small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <span style="margin-top: 40px;">
                            <button class="btn btn-success" type="submit">Submit</button>
                        </span>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script type="text/javascript">

    $("ul#account").siblings('a').attr('aria-expanded','true');
    $("ul#account").addClass("show");
    $("ul#account #tutup-buku-menu").addClass("active");

</script>
@endpush