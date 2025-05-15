@extends('backend.layout.main') @section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif

    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}
        </div>
    @endif
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ trans('file.POS Setting') }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic">
                                <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                            </p>
                            {!! Form::open(['route' => 'setting.posStore', 'method' => 'post']) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Default Customer') }} *</label>
                                        @if ($lims_pos_setting_data)
                                            <input type="hidden" name="customer_id_hidden"
                                                value="{{ $lims_pos_setting_data->customer_id }}">
                                        @endif
                                        <select required name="customer_id" id="customer_id"
                                            class="selectpicker form-control" data-live-search="true"
                                            data-live-search-style="begins" title="Select customer...">
                                            @foreach ($lims_customer_list as $customer)
                                                <option value="{{ $customer->id }}">
                                                    {{ $customer->name . ' (' . $customer->phone_number . ')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Default Warehouse') }} *</label>
                                        @if ($lims_pos_setting_data)
                                            <input type="hidden" name="warehouse_id_hidden"
                                                value="{{ $lims_pos_setting_data->warehouse_id }}">
                                        @endif
                                        <select required name="warehouse_id" class="selectpicker form-control"
                                            data-live-search="true" data-live-search-style="begins"
                                            title="Select warehouse...">
                                            @foreach ($lims_warehouse_list as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Default Biller') }} *</label>
                                        @if ($lims_pos_setting_data)
                                            <input type="hidden" name="biller_id_hidden"
                                                value="{{ $lims_pos_setting_data->biller_id }}">
                                        @endif
                                        <select required name="biller_id" class="selectpicker form-control"
                                            data-live-search="true" data-live-search-style="begins"
                                            title="Select Biller...">
                                            @foreach ($lims_biller_list as $biller)
                                                <option value="{{ $biller->id }}">
                                                    {{ $biller->name . ' (' . $biller->company_name . ')' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('file.Displayed Number of Product Row') }} *</label>
                                        <input type="number" name="product_number" class="form-control"
                                            value="{{ $lims_pos_setting_data ? $lims_pos_setting_data->product_number : 0 }}"
                                            required />
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <div>
                                    <div class="form-group mb-0">
                                        <input class="mt-2" type="checkbox" name="keybord_active" id="keybord_active"
                                            value="1"
                                            {{ $lims_pos_setting_data && $lims_pos_setting_data->keybord_active ? 'checked' : '' }}>
                                        <label class="mt-2" for="keybord_active">
                                            <strong>{{ trans('file.Touchscreen keybord') }}</strong>
                                        </label>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <input type="submit" value="{{ trans('file.submit') }}" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        $("ul#setting").siblings('a').attr('aria-expanded', 'true');
        $("ul#setting").addClass("show");
        $("ul#setting #pos-setting-menu").addClass("active");



        $('select[name="customer_id"]').val($("input[name='customer_id_hidden']").val());
        $('select[name="biller_id"]').val($("input[name='biller_id_hidden']").val());
        $('select[name="warehouse_id"]').val($("input[name='warehouse_id_hidden']").val());
        $('.selectpicker').selectpicker('refresh');
    </script>
@endpush
