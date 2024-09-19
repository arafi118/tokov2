@extends('backend.layout.administrator')
@section('content')
<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="activity"></i></div>
                        Tenant
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
                        <div class="col-md-2">
                            <a href="{{route('admin.tenant.create')}}"><button type="button" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i>&nbsp;Tambah</button></a>
                        </div>
                        <div class="col-md-6"></div>
                       <!--  <div class="col-md-4">
                              <div class="input-group input-group-joined input-group-solid">
                                <input  class="form-control" type="search" id="search" placeholder="Search" aria-label="Search" />
                                <div class="input-group-text"><i data-feather="search"></i></div>
                             </div>
                        </div> -->
                    </div>
                </div>
                <div class="card-body">
                    @if(session('status'))
                    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session('status') }}</div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <table id="" style="width:100%;"  class="table table-striped">
                                <thead style="background-color: #052963; color:#fff;">
                                    <tr>
                                        <td>Aksi</td>
                                        <td>FQDN</td>
                                        <td>UUID</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($data->count() > 0)
                                    @foreach($data as $d)
                                    <tr>
                                        <td><button onclick="deleteTenant('{{$d->id}}')" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    
                                        <td>{{$d->fqdn}}</td>
                                        <td>{{$d->uuid}}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="3" style="text-align:center;">- Belum ada Tenant / Toko-</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{$data->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
  
 var roleTable;

    $(document).ready(function() {

        <?php if(session('status')): ?>
          
            toastSuccess('{{session("status")}}');;
            
        <?php endif ?>
        roleTable = $('#roleTable').DataTable({
            iDisplayLength: 10,
            processing: true,
            serverSide: true,
            stateSave: true,
            scrollX: true,
            dom: 'rtip',
            ajax: {
                url: "{{route('admin.tenant.getdata')}}",
                dataType: "json",
                type: "POST",
                data: function(d) {
                    d._token = "{{csrf_token()}}";
                    d.search = $('#search').val();
                }
            },
            columnDefs: [{
               // "className": "text-muted", "targets": "_all",
                searchable: false,
                orderable: false,
                targets: 0
            }],
            order:[[1,'desc']],
            columns: [
                {
                    data: 'action',
                    orderable: false,
                    className:'text-center',
                    width: '100',
                },
                {
                    data: 'fqdn',
                    width: '200'
                },
                {
                    data: 'uuid',
                    width: '200'
                },
            ],
           
            "createdRow": function (row, data, dataIndex) {
              //$(row).addClass('text-gray-800 border border-gray-200 rounded-lg');
            }
        })
        .on('xhr.dt', function (e, settings, json, xhr) {});

        $('#search').on('keyup',function(){
            roleTable.draw();
        });
    });


    function deleteTenant(id){
        if(confirm('Anda yakin akan menghapus Tenant / Toko secara permanen ?')){
            location.href = '{{url("admin/tenant/delete")}}?id='+id;
        }
    }
</script>
@endpush