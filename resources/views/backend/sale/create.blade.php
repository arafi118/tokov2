@extends('backend.layout.main') @section('content')
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>{{ session()->get('not_permitted') }}
        </div>
    @endif
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ trans('file.Add Sale') }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic">
                                <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                            </p>
                            {!! Form::open(['route' => 'sales.store', 'method' => 'post', 'files' => true, 'class' => 'payment-form']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ trans('file.Date') }}</label>
                                                <input type="text" name="created_at" value="{{ date('d-m-Y') }}"
                                                    class="form-control date" placeholder="Choose date" />
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="display:none;">
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('file.Reference No') }}
                                                </label>
                                                <input type="text" name="reference_no" class="form-control" />
                                            </div>
                                            @if ($errors->has('reference_no'))
                                                <span>
                                                    <strong>{{ $errors->first('reference_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ trans('file.customer') }} *</label>
                                                <select required name="customer_id" id="customer_id"
                                                    class="selectpicker form-control" data-live-search="true"
                                                    title="Select customer...">
                                                    <?php
                                                    $deposit = [];
                                                    $points = [];
                                                    ?>
                                                    @foreach ($lims_customer_list as $customer)
                                                        <?php
                                                        $deposit[$customer->id] = $customer->deposit - $customer->expense;
                                                        
                                                        $points[$customer->id] = $customer->points;
                                                        ?>
                                                        <option value="{{ $customer->id }}">
                                                            {{ $customer->name . ' (' . $customer->phone_number . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ trans('file.Warehouse') }} *</label>
                                                <select required name="warehouse_id" id="warehouse_id"
                                                    class="selectpicker form-control" data-live-search="true"
                                                    data-live-search-style="begins" title="Select warehouse...">
                                                    @foreach ($lims_warehouse_list as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ trans('file.Biller') }} *</label>
                                                <select required name="biller_id" class="selectpicker form-control"
                                                    data-live-search="true" data-live-search-style="begins"
                                                    title="Select Biller...">
                                                    @foreach ($lims_biller_list as $biller)
                                                        <option value="{{ $biller->id }}">
                                                            {{ $biller->name . ' (' . $biller->company_name . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Payment Status') }} *</label>
                                                <select name="payment_status" class="form-control">
                                                    <option value="1">{{ trans('file.Pending') }}</option>
                                                    <option value="2">{{ trans('file.Due') }}</option>
                                                    <option value="3">{{ trans('file.Partial') }}</option>
                                                    <option selected value="4">{{ trans('file.Paid') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Attach Document') }}</label> <i
                                                    class="dripicons-question" data-toggle="tooltip"
                                                    title="Only jpg, jpeg, png, gif, pdf, csv, docx, xlsx and txt file is supported"></i>
                                                <input type="file" name="document" class="form-control" />
                                                @if ($errors->has('extension'))
                                                    <span>
                                                        <strong>{{ $errors->first('extension') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="is_po">Pre Order</label>
                                            <div class="form-group">
                                                <label for="is_po" class="switch">
                                                    <input type="checkbox" class="default" name="is_po" id="is_po"
                                                        onclick="checkPo();">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <input type="hidden" name="sale_status" id="sale_status" value="1">
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>{{ trans('file.Select Product') }}</label>
                                            <div class="search-box input-group">
                                                <button type="button" class="btn btn-secondary btn-lg"><i
                                                        class="fa fa-barcode"></i></button>
                                                <input type="text" name="product_code_name" id="lims_productcodeSearch"
                                                    placeholder="Please type product code and select..."
                                                    class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <h5>{{ trans('file.Order Table') }} *</h5>
                                            <div class="table-responsive mt-3">
                                                <table id="myTable" class="table table-hover order-list">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ trans('file.name') }}</th>
                                                            <th>{{ trans('file.Code') }}</th>
                                                            <th width="100">{{ trans('file.Quantity') }}</th>
                                                            <th>{{ trans('file.Batch No') }}</th>
                                                            <th>{{ trans('file.Net Unit Price') }}</th>
                                                            <th>{{ trans('file.Discount') }}</th>
                                                            <th>{{ trans('file.Cashback') }}</th>
                                                            <th>{{ trans('file.Tax') }}</th>
                                                            <th>{{ trans('file.Subtotal') }}</th>
                                                            <th><i class="dripicons-trash"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot class="tfoot active">
                                                        <th colspan="2">{{ trans('file.Total') }}</th>
                                                        <th id="total-qty">0</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th id="total-discount">0.00</th>
                                                        <th id="total-cashback">0.00</th>
                                                        <th id="total-tax">0.00</th>
                                                        <th id="total">0.00</th>
                                                        <th><i class="dripicons-trash"></i></th>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_qty" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_discount" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_cashback" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_tax" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_price" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="item" />
                                                <input type="hidden" name="order_tax" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="grand_total">
                                                <input type="hidden" name="used_points">
                                                <input type="hidden" name="pos" value="0">
                                                <input type="hidden" name="coupon_active" value="0">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Order Discount Type') }}</label>
                                                        <select id="order-discount-type" name="order_discount_type"
                                                            class="form-control">
                                                            <option value="Flat">{{ trans('file.Flat') }}</option>
                                                            <option value="Percentage">{{ trans('file.Percentage') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Value') }}</label>
                                                        <input type="text" name="order_discount_value"
                                                            class="form-control mask" id="order-discount-val">
                                                        <input type="hidden" name="order_discount" class="form-control"
                                                            id="order-discount">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Order Cashback Type') }}</label>
                                                        <select id="order-cashback-type" name="order_cashback_type"
                                                            class="form-control">
                                                            <option value="Flat">{{ trans('file.Flat') }}</option>
                                                            <option value="Percentage">{{ trans('file.Percentage') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Value') }}</label>
                                                        <input type="text" name="order_cashback_value"
                                                            class="form-control mask" id="order-cashback-val">
                                                        <input type="hidden" name="order_cashback" class="form-control"
                                                            id="order-cashback">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Order Tax') }}</label>
                                                        <select class="form-control" name="order_tax_rate">
                                                            <option value="0">No Tax</option>
                                                            @foreach ($lims_tax_list as $tax)
                                                                <option value="{{ $tax->rate }}">{{ $tax->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>
                                                            {{ trans('file.Shipping Cost') }}
                                                        </label>
                                                        <input type="text" name="shipping_cost"
                                                            class="form-control decimal" step="any" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ trans('file.Sale Note') }}</label>
                                                <textarea rows="5" class="form-control" name="sale_note"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ trans('file.Staff Note') }}</label>
                                                <textarea rows="5" class="form-control" name="staff_note"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border mt-3">
                                        <div class="card-body">
                                            <h4 class="text-center">{{ trans('file.Payment Details') }}</h4>
                                            <div class="row border-top">
                                                <div class="col-md-6">
                                                    <ul class="list-group list-group-flush">
                                                        <li
                                                            class="list-group-item pl-0 pr-0 d-flex justify-content-between">
                                                            <strong>{{ trans('file.Items') }}</strong>
                                                            <span>
                                                                <span id="item">0(0)</span>
                                                            </span>
                                                        </li>
                                                        <li
                                                            class="list-group-item pl-0 pr-0 d-flex justify-content-between">
                                                            <strong>{{ trans('file.Total') }}</strong>
                                                            <span>
                                                                Rp. <span id="subtotal">0.00</span>
                                                            </span>
                                                        </li>
                                                        <li
                                                            class="list-group-item pl-0 pr-0 d-flex justify-content-between">
                                                            <strong>{{ trans('file.Order Tax') }}</strong>
                                                            <span>
                                                                Rp. <span id="order_tax">0.00</span>
                                                            </span>
                                                        </li>
                                                        <li
                                                            class="list-group-item pl-0 pr-0 d-flex justify-content-between">
                                                            <strong>{{ trans('file.Order Discount') }}</strong>
                                                            <span>
                                                                Rp. <span id="order_discount">0.00</span>
                                                            </span>
                                                        </li>
                                                        <li
                                                            class="list-group-item pl-0 pr-0 d-flex justify-content-between">
                                                            <strong>{{ trans('file.Order Cashback') }}</strong>
                                                            <span>
                                                                Rp. <span id="order_cashback">0.00</span>
                                                            </span>
                                                        </li>
                                                        <li
                                                            class="list-group-item pl-0 pr-0 d-flex justify-content-between">
                                                            <strong>{{ trans('file.Shipping Cost') }}</strong>
                                                            <span>
                                                                Rp. <span id="shipping_cost">0.00</span>
                                                            </span>
                                                        </li>
                                                        <li
                                                            class="list-group-item pl-0 pr-0 d-flex justify-content-between">
                                                            <strong>{{ trans('file.grand total') }}</strong>
                                                            <span>
                                                                Rp. <span id="grand_total">0.00</span>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6 d-flex flex-column justify-content-between">
                                                    <div id="payment">
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>{{ trans('file.Paid By') }}</label>
                                                                    <select name="paid_by_id" id="paid_by_id_add"
                                                                        class="form-control">
                                                                        {{-- 
                                                                            <option value="1">Cash</option>
                                                                            <option value="2">Gift Card</option>
                                                                            <option value="3">Credit Card</option>
                                                                            <option value="4">Cheque</option>
                                                                            <option value="5">Paypal</option>
                                                                            <option value="6">Deposit</option>
                                                                            @if ($lims_reward_point_setting_data->is_active)
                                                                            <option value="7">Points</option>
                                                                            @endif  
                                                                        --}}
                                                                        <option value="1">Cash</option>
                                                                        {{-- <option value="3">Credit Card</option> --}}
                                                                        {{-- <option value="4">Cheque</option> --}}
                                                                        <option value="5">Debit Card</option>
                                                                        {{-- <option value="6">Tempo / Utang</option> --}}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>{{ trans('file.Paying Amount') }} *</label>
                                                                    <input type="text" name="paid_amount"
                                                                        class="form-control decimal" id="paid-amount"
                                                                        step="any" />
                                                                    <input type="hidden" name="paying_amount"
                                                                        id="paying-amount" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" id="gift-card">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label> {{ trans('file.Gift Card') }} *</label>
                                                                    <select id="gift_card_id" name="gift_card_id"
                                                                        class="selectpicker form-control"
                                                                        data-live-search="true"
                                                                        data-live-search-style="begins"
                                                                        title="Select Gift Card..."></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" id="debit_card">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Transfer / Debit Card</label>
                                                                    <select class="form-control selectpicker"
                                                                        name="no_rek_bank">
                                                                        @foreach ($rekening as $rek)
                                                                            <option value="{{ $rek->id }}">
                                                                                {{ $rek->nama }} -
                                                                                {{ $rek->no_rek_bank }} -
                                                                                {{ $rek->atas_nama_rek }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" id="cheque">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>{{ trans('file.Cheque Number') }} *</label>
                                                                    <input type="text" name="cheque_no"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>{{ trans('file.Payment Note') }}</label>
                                                                <textarea rows="3" class="form-control" name="payment_note"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-end mt-3">
                                                        <input type="submit" value="{{ trans('file.submit') }}"
                                                            class="btn btn-primary" id="submit-button">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal_header" class="modal-title"></h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row modal-element">
                                <div class="col-md-4 form-group">
                                    <label>{{ trans('file.Quantity') }}</label>
                                    <input type="number" step="any" name="edit_qty" class="form-control numkey">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>{{ trans('file.Unit Discount') }}</label>
                                    <input type="number" name="edit_discount" class="form-control numkey">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>{{ trans('file.Unit Cashback') }}</label>
                                    <input type="number" name="edit_cashback" class="form-control numkey">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>{{ trans('file.Unit Price') }}</label>
                                    <input type="number" name="edit_unit_price" class="form-control numkey"
                                        step="any">
                                </div>
                                @php
                                    $tax_name_all[] = 'No Tax';
                                    $tax_rate_all[] = 0;
                                    foreach ($lims_tax_list as $tax) {
                                        $tax_name_all[] = $tax->name;
                                        $tax_rate_all[] = $tax->rate;
                                    }
                                @endphp
                                <div class="col-md-4 form-group">
                                    <label>{{ trans('file.Tax Rate') }}</label>
                                    <select name="edit_tax_rate" class="form-control selectpicker">
                                        @foreach ($tax_name_all as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="edit_unit" class="col-md-4 form-group">
                                    <label>{{ trans('file.Product Unit') }}</label>
                                    <select name="edit_unit" class="form-control selectpicker">
                                    </select>
                                </div>
                            </div>
                            <button type="button" name="update_btn" class="btn btn-primary">
                                {{ trans('file.update') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- add cash register modal -->
        <div id="cash-register-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => 'cashRegister.store', 'method' => 'post']) !!}
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Cash Register') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        <div class="row">
                            <div class="col-md-6 form-group warehouse-section">
                                <label>{{ trans('file.Warehouse') }} *</strong> </label>
                                <select required name="warehouse_id" class="selectpicker form-control"
                                    data-live-search="true" data-live-search-style="begins" title="Select warehouse...">
                                    @foreach ($lims_warehouse_list as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Cash in Hand') }} *</strong> </label>
                                <input type="number" name="cash_in_hand" required class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        $("ul#sale").siblings('a').attr('aria-expanded', 'true');
        $("ul#sale").addClass("show");
        $("ul#sale #sale-create-menu").addClass("active");

        var public_key = JSON.parse('{!! json_encode($lims_pos_setting_data->stripe_public_key) !!}');
        var currency = JSON.parse('{!! json_encode($currency) !!}');

        $(".card-element").hide();
        $("#gift-card").hide();
        $("#cheque").hide();
        $("#debit_card").hide();

        // array data depend on warehouse
        var lims_product_array = [];
        var product_code = [];
        var product_name = [];
        var product_qty = [];
        var product_type = [];
        var product_id = [];
        var product_list = [];
        var variant_list = [];
        var qty_list = [];

        // array data with selection
        var product_price = [];
        var product_discount = [];
        var product_cashback = [];
        var tax_rate = [];
        var tax_name = [];
        var tax_method = [];
        var unit_name = [];
        var unit_operator = [];
        var unit_operation_value = [];
        var is_imei = [];
        var is_variant = [];
        var gift_card_amount = [];
        var gift_card_expense = [];
        // temporary array
        var temp_unit_name = [];
        var temp_unit_operator = [];
        var temp_unit_operation_value = [];

        var deposit = JSON.parse('{!! json_encode($deposit) !!}');
        var points = JSON.parse('{!! json_encode($points) !!}');
        var reward_point_setting = JSON.parse('{!! json_encode($lims_reward_point_setting_data) !!}');

        var rowindex;
        var customer_group_rate;
        var row_product_price;
        var pos;
        var role_id = JSON.parse('{!! json_encode(Auth::user()->role_id) !!}');

        $('.selectpicker').selectpicker({
            style: 'btn-link',
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('select[name="customer_id"]').on('change', function() {
            var id = $(this).val();
            $.get('getcustomergroup/' + id, function(data) {
                customer_group_rate = (data / 100);
            });
        });

        $('#is_po').on('change', function() {
            if ($(this).is(':checked')) {
                $('#sale_status').val('2');
            } else {
                $('#sale_status').val('1');
            }

            checkPo();
        })

        $('select[name="warehouse_id"]').on('change', function() {
            var id = $(this).val();
            $.get('getproduct/' + id, function(data) {
                lims_product_array = [];
                product_code = data[0];
                product_name = data[1];
                product_qty = data[2];
                product_type = data[3];
                product_id = data[4];
                product_list = data[5];
                qty_list = data[6];
                product_warehouse_price = data[7];
                batch_no = data[8];
                product_batch_id = data[9];
                expired_date = data[10];
                is_embeded = data[11];
                $.each(product_code, function(index) {
                    if (is_embeded[index])
                        lims_product_array.push(product_code[index] + ' (' + product_name[index] +
                            ')|' + is_embeded[index]);
                    else
                        lims_product_array.push(product_code[index] + ' (' + product_name[index] +
                            ')');
                });
            });

            isCashRegisterAvailable(id);
        });

        $('#lims_productcodeSearch').on('input', function() {
            var customer_id = $('#customer_id').val();
            var warehouse_id = $('#warehouse_id').val();
            temp_data = $('#lims_productcodeSearch').val();
            if (!customer_id) {
                $('#lims_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
                swal('Alert', 'Please select Customer!', 'error');
            } else if (!warehouse_id) {
                $('#lims_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
                swal('Alert', 'Please select Warehouse!', 'error');
            }

        });

        var lims_productcodeSearch = $('#lims_productcodeSearch');

        lims_productcodeSearch.autocomplete({
            source: function(request, response) {
                var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
                response($.grep(lims_product_array, function(item) {
                    return matcher.test(item);
                }));
            },
            response: function(event, ui) {
                if (ui.content.length == 1) {
                    var data = ui.content[0].value;
                    $(this).autocomplete("close");
                    productSearch(data);
                } else if (ui.content.length == 0 && $('#lims_productcodeSearch').val().length == 13) {
                    productSearch($('#lims_productcodeSearch').val() + '|' + 1);
                }
            },
            select: function(event, ui) {
                var data = ui.item.value;
                productSearch(data);
            }
        });

        //Change quantity
        $("#myTable").on('input', '.qty', function() {
            rowindex = $(this).closest('tr').index();
            if ($(this).val() < 0 && $(this).val() != '') {
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(1);
                alert("Quantity can't be less than 0");
            }
            if (is_variant[rowindex])
                checkQuantity($(this).val(), true);
            else
                checkDiscount($(this).val(), true);
            //checkQuantity($(this).val(), true);
        });


        //Delete product
        $("table.order-list tbody").on("click", ".ibtnDel", function(event) {
            rowindex = $(this).closest('tr').index();
            product_price.splice(rowindex, 1);
            product_discount.splice(rowindex, 1);
            product_cashback.splice(rowindex, 1);
            tax_rate.splice(rowindex, 1);
            tax_name.splice(rowindex, 1);
            tax_method.splice(rowindex, 1);
            unit_name.splice(rowindex, 1);
            unit_operator.splice(rowindex, 1);
            unit_operation_value.splice(rowindex, 1);
            $(this).closest("tr").remove();
            calculateTotal();
        });

        //Edit product
        $("table.order-list").on("click", ".edit-product", function() {
            rowindex = $(this).closest('tr').index();
            edit();
        });

        //Update product
        $('button[name="update_btn"]').on("click", function() {
            if (is_imei[rowindex]) {
                var imeiNumbers = $("#editModal input[name=imei_numbers]").val();
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.imei-number').val(
                    imeiNumbers);
            }

            var edit_discount = toNumber($('input[name="edit_discount"]').val());
            var edit_cashback = toNumber($('input[name="edit_cashback"]').val());
            var edit_qty = $('input[name="edit_qty"]').val();
            var edit_unit_price = toNumber($('input[name="edit_unit_price"]').val());

            if (parseFloat(edit_discount) > parseFloat(edit_unit_price)) {
                alert('Invalid Discount Input!');
                return;
            }

            if (parseFloat(edit_cashback) > parseFloat(edit_unit_price)) {
                alert('Invalid Cashback Input!');
                return;
            }

            if (edit_qty < 1) {
                $('input[name="edit_qty"]').val(1);
                edit_qty = 1;
                alert("Quantity can't be less than 1");
            }

            var tax_rate_all = JSON.parse('{!! json_encode($tax_rate_all) !!}');
            tax_rate[rowindex] = parseFloat(tax_rate_all[$('select[name="edit_tax_rate"]').val()]);
            tax_name[rowindex] = $('select[name="edit_tax_rate"] option:selected').text();
            if (product_type[pos] == 'standard') {
                var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(","));
                var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[
                    rowindex].indexOf(","));
                if (row_unit_operator == '*') {
                    product_price[rowindex] = toNumber($('input[name="edit_unit_price"]').val()) /
                        row_unit_operation_value;
                } else {
                    product_price[rowindex] = toNumber($('input[name="edit_unit_price"]').val()) *
                        row_unit_operation_value;
                }
                var position = $('select[name="edit_unit"]').val();
                var temp_operator = temp_unit_operator[position];
                var temp_operation_value = temp_unit_operation_value[position];
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sale-unit').val(
                    temp_unit_name[position]);
                temp_unit_name.splice(position, 1);
                temp_unit_operator.splice(position, 1);
                temp_unit_operation_value.splice(position, 1);

                temp_unit_name.unshift($('select[name="edit_unit"] option:selected').text());
                temp_unit_operator.unshift(temp_operator);
                temp_unit_operation_value.unshift(temp_operation_value);

                unit_name[rowindex] = temp_unit_name.toString() + ',';
                unit_operator[rowindex] = temp_unit_operator.toString() + ',';
                unit_operation_value[rowindex] = temp_unit_operation_value.toString() + ',';
            } else {
                product_price[rowindex] = toNumber($('input[name="edit_unit_price"]').val());
            }
            product_discount[rowindex] = toNumber($('input[name="edit_discount"]').val());
            product_cashback[rowindex] = toNumber($('input[name="edit_cashback"]').val());
            checkDiscount(edit_qty, false);
            //checkQuantity(edit_qty, false);
        });

        $("#myTable").on("change", ".batch-no", function() {
            rowindex = $(this).closest('tr').index();
            var product_id = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-id')
                .val();
            var warehouse_id = $('#warehouse_id').val();
            $.get('../check-batch-availability/' + product_id + '/' + $(this).val() + '/' + warehouse_id, function(
                data) {
                if (data['message'] != 'ok') {
                    alert(data['message']);
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.batch-no').val(
                        '');
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                        '.product-batch-id').val('');
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.expired-date')
                        .text('');
                } else {
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                        '.product-batch-id').val(data['product_batch_id']);
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.expired-date')
                        .text(data['expired_date']);
                    code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                        '.product-code').val();
                    pos = product_code.indexOf(code);
                    product_qty[pos] = data['qty'];
                }
            });
        });

        function isCashRegisterAvailable(warehouse_id) {
            $.ajax({
                url: '../cash-register/check-availability/' + warehouse_id,
                type: "GET",
                success: function(data) {
                    if (data == 'false') {
                        $('#cash-register-modal select[name=warehouse_id]').val(warehouse_id);
                        $('.selectpicker').selectpicker('refresh');
                        if (role_id <= 2) {
                            $("#cash-register-modal .warehouse-section").removeClass('d-none');
                        } else {
                            $("#cash-register-modal .warehouse-section").addClass('d-none');
                        }
                        $("#cash-register-modal").modal('show');
                    }
                }
            });
        }

        function productSearch(data) {
            var product_info = data.split(" ");
            var code = product_info[0];
            var pre_qty = 0;

            $(".product-code").each(function(i) {

                if ($(this).val() == code) {
                    rowindex = i;
                    pre_qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val();
                }
            });
            data += '?' + $('#customer_id').val() + '?' + (parseFloat(pre_qty) + 1);

            $.ajax({
                type: 'GET',
                url: 'lims_product_search',
                data: {
                    data: data
                },
                success: function(data) {
                    var flag = 1;
                    if (pre_qty > 0) {

                        var qty = data[15];
                        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
                        pos = product_code.indexOf(data[1]);
                        if (!data[11] && product_warehouse_price[pos]) {
                            product_price[rowindex] = parseFloat(product_warehouse_price[pos] * currency[
                                'exchange_rate']) + parseFloat(product_warehouse_price[pos] * currency[
                                'exchange_rate'] * customer_group_rate);
                        } else {
                            product_price[rowindex] = parseFloat(data[2] * currency['exchange_rate']) +
                                parseFloat(data[2] * currency['exchange_rate'] * customer_group_rate);
                        }
                        flag = 0;
                        checkQuantity(String(qty), true);
                        flag = 0;
                    }
                    $("input[name='product_code_name']").val('');
                    if (flag) {

                        var newRow = $("<tr>");
                        var cols = '';
                        pos = product_code.indexOf(data[1]);
                        temp_unit_name = (data[6]).split(',');

                        var expired = '<div class="text-danger small expired-date">Kadaluwarsa: N/A</div>';
                        if (data[12]) {
                            expired = '<div class="text-danger small expired-date">Kadaluwarsa: ' +
                                expired_date[pos] + '</div>';
                        }

                        cols += '<td>' + data[0] +
                            '<button type="button" class="edit-product btn btn-link" data-toggle="modal" data-target="#editModal"> <i class="dripicons-document-edit"></i></button>' +
                            expired + '</td>';
                        cols += '<td>' + data[1] + '</td>';
                        cols +=
                            '<td><input type="number" class="form-control form-control-sm qty" name="qty[]" value="' +
                            data[
                                15] + '" step="any" required/></td>';
                        if (data[12]) {
                            cols +=
                                '<td><input type="text" class="form-control form-control-sm batch-no" value="' +
                                batch_no[pos] +
                                '" required/> <input type="hidden" class="product-batch-id" name="product_batch_id[]" value="' +
                                product_batch_id[pos] + '"/> </td>';
                        } else {
                            cols +=
                                '<td><input type="text" class="form-control form-control-sm batch-no" disabled/> <input type="hidden" class="product-batch-id" name="product_batch_id[]"/> </td>';
                        }

                        cols += '<td class="net_unit_price"></td>';
                        cols += '<td class="discount">0</td>';
                        cols += '<td class="cashback">0</td>';
                        cols += '<td class="tax"></td>';
                        cols += '<td class="sub-total"></td>';
                        cols +=
                            '<td><button type="button" class="ibtnDel btn btn-sm btn-danger"><i class="dripicons-trash"></i></button></td>';
                        cols += '<input type="hidden" class="product-code" name="product_code[]" value="' +
                            data[1] + '"/>';
                        cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[
                            9] + '"/>';
                        cols += '<input type="hidden" class="sale-unit" name="sale_unit[]" value="' +
                            temp_unit_name[0] + '"/>';
                        cols += '<input type="hidden" class="net_unit_price" name="net_unit_price[]" />';
                        cols += '<input type="hidden" class="discount-value" name="discount[]" />';
                        cols += '<input type="hidden" class="cashback-value" name="cashback[]" />';
                        cols += '<input type="hidden" class="tax-rate" name="tax_rate[]" value="' + data[3] +
                            '"/>';
                        cols += '<input type="hidden" class="tax-value" name="tax[]" />';
                        cols += '<input type="hidden" class="subtotal-value" name="subtotal[]" />';
                        cols += '<input type="hidden" class="imei-number" name="imei_number[]" />';

                        newRow.append(cols);
                        $("table.order-list tbody").prepend(newRow);
                        rowindex = newRow.index();

                        if (!data[11] && product_warehouse_price[pos]) {
                            product_price.splice(rowindex, 0, parseFloat(product_warehouse_price[pos] *
                                currency['exchange_rate']) + parseFloat(product_warehouse_price[pos] *
                                currency['exchange_rate'] * customer_group_rate));
                        } else {
                            product_price.splice(rowindex, 0, parseFloat(data[2] * currency['exchange_rate']) +
                                parseFloat(data[2] * currency['exchange_rate'] * customer_group_rate));
                        }

                        product_discount.splice(rowindex, 0, '0.00');
                        product_cashback.splice(rowindex, 0, '0.00');
                        tax_rate.splice(rowindex, 0, parseFloat(data[3]));
                        tax_name.splice(rowindex, 0, data[4]);
                        tax_method.splice(rowindex, 0, data[5]);
                        unit_name.splice(rowindex, 0, data[6]);
                        unit_operator.splice(rowindex, 0, data[7]);
                        unit_operation_value.splice(rowindex, 0, data[8]);
                        is_imei.splice(rowindex, 0, data[13]);
                        is_variant.splice(rowindex, 0, data[14]);
                        checkQuantity(data[15], true);
                        if (data[13]) {
                            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                                '.edit-product').click();
                        }
                    }
                }
            });
        }

        function edit() {
            $(".imei-section").remove();
            if (is_imei[rowindex]) {
                var imeiNumbers = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.imei-number')
                    .val();

                htmlText =
                    '<div class="col-md-12 form-group imei-section"><label>IMEI or Serial Numbers</label><input type="text" name="imei_numbers" value="' +
                    imeiNumbers +
                    '" class="form-control imei_number" placeholder="Type imei or serial numbers and separate them by comma. Example:1001,2001" step="any"></div>';
                $("#editModal .modal-element").append(htmlText);
            }

            var row_product_name = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(1)')
                .text();
            var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)')
                .text();
            $('#modal_header').text(row_product_name + '(' + row_product_code + ')');

            var qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val();
            $('input[name="edit_qty"]').val(qty);

            $('input[name="edit_discount"]').val(toDecimal(product_discount[rowindex]));
            $('input[name="edit_cashback"]').val(toDecimal(product_cashback[rowindex]));

            var tax_name_all = JSON.parse('{!! json_encode($tax_name_all) !!}');
            pos = tax_name_all.indexOf(tax_name[rowindex]);
            $('select[name="edit_tax_rate"]').val(pos);

            pos = product_code.indexOf(row_product_code);
            if (product_type[pos] == 'standard') {
                unitConversion();
                temp_unit_name = (unit_name[rowindex]).split(',');
                temp_unit_name.pop();
                temp_unit_operator = (unit_operator[rowindex]).split(',');
                temp_unit_operator.pop();
                temp_unit_operation_value = (unit_operation_value[rowindex]).split(',');
                temp_unit_operation_value.pop();
                $('select[name="edit_unit"]').empty();
                $.each(temp_unit_name, function(key, value) {
                    $('select[name="edit_unit"]').append('<option value="' + key + '">' + value + '</option>');
                });
                $("#edit_unit").show();
            } else {
                row_product_price = product_price[rowindex];
                $("#edit_unit").hide();
            }
            $('input[name="edit_unit_price"]').val(toDecimal(row_product_price));
            $('.selectpicker').selectpicker('refresh');
        }

        function checkDiscount(qty, flag) {
            var customer_id = $('#customer_id').val();
            var product_id = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .product-id').val();
            if (flag) {
                $.ajax({
                    type: 'GET',
                    async: false,
                    url: '../sales/check-discount?qty=' + qty + '&customer_id=' + customer_id + '&product_id=' +
                        product_id,
                    success: function(data) {
                        pos = product_code.indexOf($('table.order-list tbody tr:nth-child(' + (rowindex + 1) +
                            ') .product-code').val());
                        product_price[rowindex] = parseFloat(data[0] * currency['exchange_rate']) + parseFloat(
                            data[0] * currency['exchange_rate'] * customer_group_rate);
                    }
                });
            }
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
            checkQuantity(String(qty), flag);
        }

        function checkQuantity(sale_qty, flag) {
            var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)')
                .text();

            pos = product_code.indexOf(row_product_code);
            if (product_type[pos] == 'standard') {
                var operator = unit_operator[rowindex].split(',');
                var operation_value = unit_operation_value[rowindex].split(',');
                if (operator[0] == '*') {

                    total_qty = sale_qty * operation_value[0];
                } else if (operator[0] == '/') {

                    total_qty = sale_qty / operation_value[0];
                }
                if (total_qty > parseFloat(product_qty[pos])) {
                    swal('Alert', 'Quantity exceeds stock quantity!', 'error');
                    if (flag) {
                        sale_qty = sale_qty.substring(0, sale_qty.length - 1);
                        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(sale_qty);
                    } else {

                        edit();
                        return;
                    }
                }
            } else if (product_type[pos] == 'combo') {
                child_id = product_list[pos].split(',');
                child_qty = qty_list[pos].split(',');
                $(child_id).each(function(index) {
                    var position = product_id.indexOf(parseInt(child_id[index]));
                    if (parseFloat(sale_qty * child_qty[index]) > product_qty[position]) {
                        alert('Quantity exceeds stock quantity!');
                        if (flag) {
                            sale_qty = sale_qty.substring(0, sale_qty.length - 1);
                            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(
                                sale_qty);
                        } else {
                            edit();
                            flag = true;
                            return false;
                        }
                    }
                });
            }

            if (!flag) {
                $('#editModal').modal('hide');
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(sale_qty);
            }

            calculateRowProductData(sale_qty);
        }

        function calculateRowProductData(quantity) {

            if (product_type[pos] == 'standard') {
                unitConversion();
            } else {
                row_product_price = product_price[rowindex];
            }

            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount').text(
                (toNumber(product_discount[rowindex]) * quantity));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount-value').val(
                (toNumber(product_discount[rowindex]) * quantity));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.cashback').text(
                (toNumber(product_cashback[rowindex]) * quantity));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.cashback-value').val(
                (toNumber(product_cashback[rowindex]) * quantity));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val(
                tax_rate[rowindex]);

            if (tax_method[rowindex] == 1) {
                var net_unit_price = row_product_price - product_discount[rowindex];
                var tax = net_unit_price * quantity * (tax_rate[rowindex] / 100);
                var sub_total = (net_unit_price * quantity) + tax;

                console.log(row_product_price, product_discount[rowindex], net_unit_price, tax, sub_total);

                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_price').text(
                    toDecimal(net_unit_price));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_price').val(
                    toDecimal(net_unit_price));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax').text(toDecimal(tax));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(toDecimal(tax));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sub-total').text(
                    toDecimal(sub_total));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total);
            } else {
                var sub_total_unit = row_product_price - product_discount[rowindex];
                var net_unit_price = (100 / (100 + tax_rate[rowindex])) * sub_total_unit;
                var tax = (sub_total_unit - net_unit_price) * quantity;
                var sub_total = sub_total_unit * quantity;

                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_price').text(
                    toDecimal(net_unit_price));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_price').val(
                    toDecimal(net_unit_price));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax').text(toDecimal(tax));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(toDecimal(tax));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sub-total').text(
                    toDecimal(sub_total));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total);
            }

            calculateTotal();
        }

        function unitConversion() {
            var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(","));
            var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[rowindex].indexOf(
                ","));

            if (row_unit_operator == '*') {
                row_product_price = product_price[rowindex] * row_unit_operation_value;
            } else {
                row_product_price = product_price[rowindex] / row_unit_operation_value;
            }
        }

        function calculateTotal() {
            var total_qty = 0;
            $(".qty").each(function() {
                if ($(this).val() == '') {
                    total_qty += 0;
                } else {
                    total_qty += parseFloat($(this).val());
                }
            });
            $("#total-qty").text(total_qty);
            $('input[name="total_qty"]').val(total_qty);

            //Sum of discount
            var total_discount = 0;
            $(".discount").each(function() {
                total_discount += toNumber($(this).text());
            });
            $("#total-discount").text(toDecimal(total_discount));
            $('input[name="total_discount"]').val(toDecimal(total_discount));

            //Sum of cashback
            var total_cashback = 0;
            $(".cashback").each(function() {
                total_cashback += toNumber($(this).text());
            });
            $("#total-cashback").text(toDecimal(total_cashback));
            $('input[name="total_cashback"]').val(toDecimal(total_cashback));

            //Sum of tax
            var total_tax = 0;
            $(".tax").each(function() {
                total_tax += toNumber($(this).text());
            });
            $("#total-tax").text(toDecimal(total_tax));
            $('input[name="total_tax"]').val(toDecimal(total_tax));

            //Sum of subtotal
            var total = 0;
            $(".sub-total").each(function() {
                total += toNumber($(this).text());
            });
            $("#total").text(toDecimal(total));
            $('input[name="total_price"]').val(total);

            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            var item = $('table.order-list tbody tr:last').index();
            var total_qty = toNumber($('#total-qty').text());
            var subtotal = toNumber($('#total').text());
            var order_tax = toNumber($('select[name="order_tax_rate"]').val());
            var shipping_cost = toNumber($('input[name="shipping_cost"]').val());

            var order_discount_type = $('select[name="order_discount_type"]').val();
            var order_discount_value = toNumber($('input[name="order_discount_value"]').val());
            if (!order_discount_value)
                order_discount_value = 0.00;

            if (order_discount_type == 'Flat')
                var order_discount = parseFloat(order_discount_value);
            else
                var order_discount = parseFloat(subtotal * (order_discount_value / 100));

            var order_cashback_type = $('select[name="order_cashback_type"]').val();
            var order_cashback_value = toNumber($('input[name="order_cashback_value"]').val());
            if (!order_cashback_value)
                order_cashback_value = 0.00;

            if (order_cashback_type == 'Flat')
                var order_cashback = parseFloat(order_cashback_value);
            else
                var order_cashback = parseFloat(subtotal * (order_cashback_value / 100));

            if (!shipping_cost)
                shipping_cost = 0.00;

            item = ++item + '(' + total_qty + ')';
            order_tax = (subtotal - order_discount) * (order_tax / 100);
            var grand_total = (subtotal + order_tax + shipping_cost) - order_discount;

            $('input[name="order_discount"]').val(order_discount);
            $('input[name="order_cashback"]').val(order_cashback);
            $('#item').text(item);
            $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
            $('#subtotal').text(toDecimal(subtotal));
            $('#order_tax').text(toDecimal(order_tax));
            $('input[name="order_tax"]').val(toDecimal(order_tax));
            $('#order_discount').text(toDecimal(order_discount));
            $('#order_cashback').text(toDecimal(order_cashback));
            $('#shipping_cost').text(toDecimal(shipping_cost));
            $('#grand_total').text(toDecimal(grand_total));
            if ($('select[name="payment_status"]').val() == 4) {
                $('#paid-amount').val(toDecimal(grand_total));
                $('#paying-amount').val(grand_total);
            }
            $('input[name="grand_total"]').val(grand_total);
        }

        $('select[name="order_discount_type"]').on("change", function() {
            calculateGrandTotal();
        });

        $('select[name="order_cashback_type"]').on("change", function() {
            calculateGrandTotal();
        });

        $('input[name="order_discount_value"]').on("change", function() {
            calculateGrandTotal();
        });

        $('input[name="order_cashback_value"]').on("change", function() {
            calculateGrandTotal();
        });

        $('input[name="shipping_cost"]').on("change", function() {
            calculateGrandTotal();
        });

        $('select[name="order_tax_rate"]').on("change", function() {
            calculateGrandTotal();
        });

        $('select[name="payment_status"]').on("change", function() {
            var payment_status = $(this).val();
            if (payment_status == 3 || payment_status == 4) {
                $("#paid-amount").prop('disabled', false);
                $("#payment").show();
                $("#paid-amount").prop('required', true);
                if (payment_status == 4) {
                    $('input[name="paid_amount"]').val($('input[name="grand_total"]').val());
                    $('input[name="paying_amount"]').val($('input[name="grand_total"]').val());
                }
            } else {
                $("#paid-amount").prop('required', false);
                $('input[name="paid_amount"]').val('');
                $("#payment").hide();
            }
        });

        $('select[name="paid_by_id"]').on("change", function() {

            var id = $(this).val();
            $('input[name="edit_cheque_no"]').attr('required', false);
            $('#edit-payment select[name="gift_card_id"]').attr('required', false);
            $(".payment-form").off("submit");
            if (id == 2) {
                $(".gift-card").show();
                $(".card-element").hide();
                $("#cheque").hide();
                $('#add-payment select[name="gift_card_id"]').attr('required', true);
            } else if (id == 3) {
                $.getScript("public/vendor/stripe/checkout.js");
                $(".card-element").show();
                $(".gift-card").hide();
                $("#cheque").hide();
            } else if (id == 4) {
                $("#cheque").show();
                $(".gift-card").hide();
                $(".card-element").hide();
                $('input[name="cheque_no"]').attr('required', true);
            } else if (id == 5) {
                // $(".card-element").hide();
                // $(".gift-card").hide();
                // $("#cheque").hide();

                $("#debit_card").show();
                $(".card-element").hide();
                $('input[name="no_rek_bank"]').attr('required', true);
            } else {
                // $(".card-element").hide();
                // $(".gift-card").hide();
                // $("#cheque").hide();
                // if(id == 6){
                //     if($('#add-payment input[name="amount"]').val() > parseFloat(deposit))
                //         alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
                // }
                // else if(id==7) {
                //     pointCalculation($('#add-payment input[name="amount"]').val());
                // }

                $("#debit_card").hide();
                $(".card-element").hide();
                $("#cheque").hide();
            }
        });

        // $('select[name="paid_by_id"]').on("change", function() {
        //     var id = $(this).val();
        //     $(".payment-form").off("submit");
        //     $('input[name="cheque_no"]').attr('required', false);
        //     $('select[name="gift_card_id"]').attr('required', false);
        //     if(id == 2) {
        //         $("#gift-card").show();
        //         $.ajax({
        //             url: 'get_gift_card',
        //             type: "GET",
        //             dataType: "json",
        //             success:function(data) {
        //                 $('select[name="gift_card_id"]').empty();
        //                 $.each(data, function(index) {
        //                     gift_card_amount[data[index]['id']] = data[index]['amount'];
        //                     gift_card_expense[data[index]['id']] = data[index]['expense'];
        //                     $('select[name="gift_card_id"]').append('<option value="'+ data[index]['id'] +'">'+ data[index]['card_no'] +'</option>');
        //                 });
        //                 $('.selectpicker').selectpicker('refresh');
        //             }
        //         });
        //         $(".card-element").hide();
        //         $("#cheque").hide();
        //         $('select[name="gift_card_id"]').attr('required', true);
        //     }
        //     else if (id == 3) {
        //         $.getScript( "../public/vendor/stripe/checkout.js" );
        //         $(".card-element").show();
        //         $("#gift-card").hide();
        //         $("#cheque").hide();
        //     }
        //     else if (id == 4) {
        //         $("#cheque").show();
        //         $("#gift-card").hide();
        //         $(".card-element").hide();
        //         $('input[name="cheque_no"]').attr('required', true);
        //     }
        //     else {
        //         $("#gift-card").hide();
        //         $(".card-element").hide();
        //         $("#cheque").hide();
        //         if (id == 6) {
        //             if($('input[name="paid_amount"]').val() > deposit[$('#customer_id').val()]){
        //                 alert('Amount exceeds customer deposit! Customer deposit : '+ deposit[$('#customer_id').val()]);
        //             }
        //         }
        //         else if (id == 7) {
        //             pointCalculation();
        //         }
        //     }
        // });

        function pointCalculation() {
            paid_amount = $('input[name=paid_amount]').val();
            required_point = Math.ceil(paid_amount / reward_point_setting['per_point_amount']);
            if (required_point > points[$('#customer_id').val()]) {
                alert('Customer does not have sufficient points. Available points: ' + points[$('#customer_id').val()]);
            } else {
                $("input[name=used_points]").val(required_point);
            }
        }

        $('select[name="gift_card_id"]').on("change", function() {
            var balance = gift_card_amount[$(this).val()] - gift_card_expense[$(this).val()];
            if ($('input[name="paid_amount"]').val() > balance) {
                alert('Amount exceeds card balance! Gift Card balance: ' + balance);
            }
        });

        $('input[name="paid_amount"]').on("change", function() {
            if (toNumber($(this).val()) > toNumber($('#grand_total').text())) {
                alert('Paying amount cannot be bigger than grand total');
                $(this).val('');
            }

            var id = $('select[name="paid_by_id"]').val();
            if (id == 2) {
                var balance = gift_card_amount[$("#gift_card_id").val()] - gift_card_expense[$(
                        "#gift_card_id")
                    .val()];
                if (toNumber($(this).val()) > balance)
                    alert('Amount exceeds card balance! Gift Card balance: ' + balance);
            } else if (id == 6) {
                if ($('input[name="paid_amount"]').val() > deposit[$('#customer_id').val()])
                    alert('Amount exceeds customer deposit! Customer deposit : ' + deposit[$('#customer_id')
                        .val()]);
            }

            $('#paying-amount').val(toNumber($(this).val()));
        });

        $(window).keydown(function(e) {
            if (e.which == 13) {
                var $targ = $(e.target);
                if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
                    var focusNext = false;
                    $(this).find(":input:visible:not([disabled],[readonly]), a").each(function() {
                        if (this === e.target) {
                            focusNext = true;
                        } else if (focusNext) {
                            $(this).focus();
                            return false;
                        }
                    });
                    return false;
                }
            }
        });

        $(document).on('submit', '.payment-form', function(e) {
            var rownumber = $('table.order-list tbody tr:last').index();

            if ($('#is_po').is(':checked') && $('input[name="sale_status"]').val() == '1') {
                alert("Penjualan PO tidak boleh langsung ber-status Completed");

                e.preventDefault();
            }

            if (rownumber < 0) {
                alert("Please insert product to order table!")
                e.preventDefault();
            } else if ($('select[name="payment_status"]').val() == 3 && toNumber($("#paid-amount").val()) ==
                toNumber($('input[name="grand_total"]').val())) {
                alert('Paying amount equals to grand total! Please change payment status.');
                e.preventDefault();
            } else {
                $("#submit-button").prop('disabled', true);
                $("#paid-amount").prop('disabled', false);
                $(".batch-no").prop('disabled', false);
            }
        })

        function checkPo() {
            if ($('select[name="payment_status"').val() == '2') {
                $('select[name="payment_status"').val('4').trigger('change');
            }
        }
    </script>
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endpush
