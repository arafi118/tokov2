<div class="col-md-7" id="salepos-payment">
    {!! Form::open(['route' => 'sales.store', 'method' => 'post', 'files' => true, 'class' => 'payment-form']) !!}
    <div class="card">
        <div class="card-body" style="padding-bottom: 0">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4" style="display:none;">
                            <div class="form-group">
                                <input type="text" name="created_at" class="form-control date"
                                    placeholder="Choose date" onkeyup='saveValue(this);' />
                            </div>
                        </div>
                        <div class="col-md-4" style="display:none;">
                            <div class="form-group">
                                <input type="text" id="reference-no" name="reference_no" class="form-control"
                                    placeholder="Type reference number" onkeyup='saveValue(this);' />
                            </div>
                            @if ($errors->has('reference_no'))
                                <span>
                                    <strong>{{ $errors->first('reference_no') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4" style="display:none;">
                            <div class="form-group">
                                @if ($lims_pos_setting_data)
                                    <input type="hidden" name="warehouse_id_hidden"
                                        value="{{ $lims_pos_setting_data->warehouse_id }}">
                                @endif
                                <select required id="warehouse_id" name="warehouse_id" class="selectpicker form-control"
                                    data-live-search="true" data-live-search-style="begins" title="Select warehouse...">
                                    @foreach ($lims_warehouse_list as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" style="display:none;">
                            <div class="form-group">
                                @if ($lims_pos_setting_data)
                                    <input type="hidden" name="biller_id_hidden"
                                        value="{{ $lims_pos_setting_data->biller_id }}">
                                @endif
                                <select required id="biller_id" name="biller_id" class="selectpicker form-control"
                                    data-live-search="true" data-live-search-style="begins" title="Select Biller...">
                                    @foreach ($lims_biller_list as $biller)
                                        <option value="{{ $biller->id }}">
                                            {{ $biller->name . ' (' . $biller->company_name . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                @if ($lims_pos_setting_data)
                                    <input type="hidden" name="customer_id_hidden"
                                        value="{{ $lims_pos_setting_data->customer_id }}">
                                @endif
                                <div class="input-group pos">
                                    <select required name="customer_id" id="customer_id"
                                        class="selectpicker form-control" data-live-search="true"
                                        title="Select customer..." {!! $customers_add_active ? 'style="width: 100px"' : '' !!}>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer['value'] }}">
                                                {{ $customer['label'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if ($customers_add_active)
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                                            data-target="#addCustomer">
                                            <i class="dripicons-plus"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="search-box form-group">
                                <input type="text" name="product_code_name" id="lims_productcodeSearch"
                                    placeholder="Scan/Search product by name/code" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="table-responsive transaction-list">
                            <table id="myTable" class="table table-hover table-striped order-list table-fixed">
                                <thead>
                                    <tr>
                                        <th class="col-sm-2">{{ trans('file.product') }}</th>
                                        <th class="col-sm-2">{{ trans('file.Batch No') }}</th>
                                        <th class="col-sm-2">{{ trans('file.Price') }}</th>
                                        <th class="col-sm-3">{{ trans('file.Quantity') }}</th>
                                        <th class="col-sm-3">{{ trans('file.Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-id">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="display: none;">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="hidden" name="total_qty" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="hidden" name="total_discount" value="0.00" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="hidden" name="total_cashback" value="0.00" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="hidden" name="total_tax" value="0.00" />
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
                                <input type="hidden" name="grand_total" />
                                <input type="hidden" name="used_points" />
                                <input type="hidden" name="coupon_discount" />
                                <input type="hidden" name="coupon_cashback" />
                                <input type="hidden" name="sale_status" value="1" />
                                <input type="hidden" name="coupon_active">
                                <input type="hidden" name="coupon_id">
                                <input type="hidden" name="coupon_discount" />
                                <input type="hidden" name="coupon_cashback" />

                                <input type="hidden" name="pos" value="1" />
                                <input type="hidden" name="draft" value="0" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12 totals" style="border-top: 2px solid #e4e6fc; padding-top: 10px;">
                        <div class="row">
                            <div class="col-sm-3">
                                <span class="totals-title">{{ trans('file.Items') }}</span>
                                <span id="item">0</span>
                            </div>
                            <div class="col-sm-3">
                                <span class="totals-title">{{ trans('file.Total') }}</span>
                                <span id="subtotal">0.00</span>
                            </div>
                            <div class="col-sm-3">
                                <span class="totals-title">
                                    {{ trans('file.Discount') }}
                                    <button type="button" class="btn btn-link btn-sm" data-toggle="modal"
                                        data-target="#order-discount-modal">
                                        <i class="dripicons-document-edit"></i>
                                    </button>
                                </span>
                                <span id="discount">0.00</span>
                            </div>
                            <div class="col-sm-3">
                                <span class="totals-title">
                                    {{ trans('file.Cashback') }}
                                    <button type="button" class="btn btn-link btn-sm" data-toggle="modal"
                                        data-target="#order-cashback-modal">
                                        <i class="dripicons-document-edit"></i>
                                    </button>
                                </span>
                                <span id="cashback">0.00</span>
                            </div>
                        </div>
                        <div class="row border-top">
                            <div class="col-sm-4">
                                <span class="totals-title">
                                    {{ trans('file.Coupon') }}
                                    <button type="button" class="btn btn-link btn-sm" data-toggle="modal"
                                        data-target="#coupon-modal">
                                        <i class="dripicons-document-edit"></i>
                                    </button>
                                </span>
                                <span id="coupon-text">0.00</span>
                            </div>
                            <div class="col-sm-4">
                                <span class="totals-title">
                                    {{ trans('file.Tax') }}
                                    <button type="button" class="btn btn-link btn-sm" data-toggle="modal"
                                        data-target="#order-tax">
                                        <i class="dripicons-document-edit"></i>
                                    </button>
                                </span>
                                <span id="tax">0.00</span>
                            </div>
                            <div class="col-sm-4">
                                <span class="totals-title">
                                    {{ trans('file.Shipping') }}
                                    <button type="button" class="btn btn-link btn-sm" data-toggle="modal"
                                        data-target="#shipping-cost-modal">
                                        <i class="dripicons-document-edit"></i>
                                    </button>
                                </span>
                                <span id="shipping-cost">0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="payment-amount">
            <h2>{{ trans('file.grand total') }} <span id="grand-total">0.00</span></h2>
        </div>
        <div class="payment-options">
            <div class="column-5">
                <button style="background: #0984e3" type="button" class="btn btn-custom payment-btn"
                    data-toggle="modal" data-target="#add-payment" id="credit-card-btn"><i
                        class="fa fa-credit-card"></i> {{ trans('file.Card') }}</button>
            </div>
            <div class="column-5">
                <button style="background: #00cec9" type="button" class="btn btn-custom payment-btn"
                    data-toggle="modal" data-target="#add-payment" id="cash-btn"><i class="fa fa-money"></i>
                    {{ trans('file.Cash') }}</button>
            </div>
            <!-- <div class="column-5">
                <button style="background-color: #213170" type="button" class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="paypal-btn"><i class="fa fa-paypal"></i> {{ trans('file.PayPal') }}</button>
            </div> -->
            <div class="column-5">
                <button style="background-color: #e28d02" type="button" class="btn btn-custom" id="draft-btn">
                    <i class="dripicons-flag"></i>
                    {{ trans('file.Draft') }}
                </button>
            </div>
            <!--  <div class="column-5">
                    <button style="background-color: #fd7272" type="button" class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="cheque-btn"><i class="fa fa-money"></i> {{ trans('file.Cheque') }}</button>
                </div>
                <div class="column-5">
                    <button style="background-color: #5f27cd" type="button" class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="gift-card-btn"><i class="fa fa-credit-card-alt"></i> {{ trans('file.Gift Card') }}</button>
                </div>
                <div class="column-5">
                    <button style="background-color: #b33771" type="button" class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="deposit-btn"><i class="fa fa-university"></i> {{ trans('file.Deposit') }}</button>
                </div> -->
            {{-- @if ($lims_reward_point_setting_data->is_active) --}}
            <!-- <div class="column-5">
                    <button style="background-color: #319398" type="button" class="btn btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="point-btn"><i class="dripicons-rocket"></i> {{ trans('file.Points') }}</button>
                </div> -->
            {{-- @endif --}}
            <div class="column-5">
                <button style="background-color: #d63031;" type="button" class="btn btn-custom" id="cancel-btn"
                    onclick="return confirmCancel()"><i class="fa fa-close"></i>
                    {{ trans('file.Cancel') }}</button>
            </div>
            <div class="column-5">
                <button style="background-color: #ffc107;" type="button" class="btn btn-custom" data-toggle="modal"
                    data-target="#recentTransaction"><i class="dripicons-clock"></i>
                    {{ trans('file.Recent Transaction') }}</button>
            </div>
        </div>
    </div>

    <!-- payment modal -->
    <div id="add-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Finalize Sale') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6 mt-1">
                                    <label>{{ trans('file.Recieved Amount') }} *</label>
                                    <input type="text" name="paying_amount" class="form-control numkey" required
                                        step="any">
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>{{ trans('file.Paying Amount') }} *</label>
                                    <input type="text" name="paid_amount" class="form-control numkey"
                                        step="any">
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label>{{ trans('file.Change') }} : </label>
                                    <p id="change" class="ml-2">0.00</p>
                                </div>
                                <div class="col-md-6 mt-1" id="cara_bayar_div">
                                    <input type="hidden" name="paid_by_id">
                                    <label>{{ trans('file.Paid By') }}</label>
                                    <!-- <select name="paid_by_id_select" class="form-control selectpicker">
                                                            <option value="1">Cash</option>
                                                            <option value="2">Gift Card</option>
                                                            <option value="3">Credit Card</option>
                                                            <option value="4">Cheque</option>
                                                            <option value="5">Paypal</option>
                                                            <option value="6">Deposit</option>
                                                            {{-- @if ($lims_reward_point_setting_data->is_active) --}}
                                                                <option value="7">Points</option>
                                                            {{-- @endif --}}
                                                        </select> -->
                                    <select style="display: none;" name="paid_by_id_select" id="paid_by_id_add"
                                        class="form-control selectpicker">
                                        <option value="1">Cash</option>
                                        <!--  <option value="3">Credit Card</option>
                                                            <option value="4">Cheque</option>
                                                            <option value="5">Debit Card</option>
                                                            <option value="6">Tempo / Utang</option> -->
                                    </select>
                                </div>
                                <div class="form-group col-md-12 mt-3">
                                    <div class="card-element form-control">
                                    </div>
                                    <div class="card-errors" role="alert"></div>
                                </div>
                                <div class="form-group col-md-12 col-md-6 mt-1 debit-card">
                                    <label>Transfer / Debit Card</label>
                                    <input type="hidden" name="no_rek_bank" id="no_rek_bank">
                                    <select class="form-control selectpicker" id="no_rek" name="no_rek">
                                        @foreach ($rekening as $rek)
                                            <option value="{{ $rek->id }}">{{ $rek->nama }} -
                                                {{ $rek->no_rek_bank }} - {{ $rek->atas_nama_rek }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12 gift-card">
                                    <label> {{ trans('file.Gift Card') }} *</label>
                                    <input type="hidden" name="gift_card_id">
                                    <select id="gift_card_id_select" name="gift_card_id_select"
                                        class="selectpicker form-control" data-live-search="true"
                                        data-live-search-style="begins" title="Select Gift Card..."></select>
                                </div>
                                <div class="form-group col-md-12 cheque">
                                    <label>{{ trans('file.Cheque Number') }} *</label>
                                    <input type="text" name="cheque_no" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>{{ trans('file.Payment Note') }}</label>
                                    <textarea id="payment_note" rows="2" class="form-control" name="payment_note"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>{{ trans('file.Sale Note') }}</label>
                                    <textarea rows="3" class="form-control" name="sale_note"></textarea>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>{{ trans('file.Staff Note') }}</label>
                                    <textarea rows="3" class="form-control" name="staff_note"></textarea>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button id="submit-btn" type="button"
                                    class="btn btn-primary">{{ trans('file.submit') }}</button>
                            </div>
                        </div>
                        <div class="col-md-2 qc" data-initial="1">
                            <h4><strong>{{ trans('file.Quick Cash') }}</strong></h4>
                            <button class="btn btn-block btn-primary qc-btn sound-btn" data-amount="10"
                                type="button">10</button>
                            <button class="btn btn-block btn-primary qc-btn sound-btn" data-amount="20"
                                type="button">20</button>
                            <button class="btn btn-block btn-primary qc-btn sound-btn" data-amount="50"
                                type="button">50</button>
                            <button class="btn btn-block btn-primary qc-btn sound-btn" data-amount="100"
                                type="button">100</button>
                            <button class="btn btn-block btn-primary qc-btn sound-btn" data-amount="500"
                                type="button">500</button>
                            <button class="btn btn-block btn-primary qc-btn sound-btn" data-amount="1000"
                                type="button">1000</button>
                            <button class="btn btn-block btn-danger qc-btn sound-btn" data-amount="0"
                                type="button">{{ trans('file.Clear') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- order_discount modal -->
    <div id="order-discount-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('file.Order Discount') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Order Discount Type') }}</label>
                            <select id="order-discount-type" name="order_discount_type_select" class="form-control">
                                <option value="Flat">{{ trans('file.Flat') }}</option>
                                <option value="Percentage">{{ trans('file.Percentage') }}</option>
                            </select>
                            <input type="hidden" name="order_discount_type">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Value') }}</label>
                            <input type="text" name="order_discount_value" class="form-control numkey"
                                id="order-discount-val" onkeyup='saveValue(this);'>
                            <input type="hidden" name="order_discount" class="form-control" id="order-discount"
                                onkeyup='saveValue(this);'>
                        </div>
                    </div>
                    <button type="button" name="order_discount_btn" class="btn btn-primary"
                        data-dismiss="modal">{{ trans('file.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- order_cashback modal -->
    <div id="order-cashback-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('file.Order Cashback') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Order Cashback Type') }}</label>
                            <select id="order-cashback-type" name="order_cashback_type_select" class="form-control">
                                <option value="Flat">{{ trans('file.Flat') }}</option>
                                <option value="Percentage">{{ trans('file.Percentage') }}</option>
                            </select>
                            <input type="hidden" name="order_cashback_type">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Value') }}</label>
                            <input type="text" name="order_cashback_value" class="form-control numkey"
                                id="order-cashback-val" onkeyup='saveValue(this);'>
                            <input type="hidden" name="order_cashback" class="form-control" id="order-cashback"
                                onkeyup='saveValue(this);'>
                        </div>
                    </div>
                    <button type="button" name="order_cashback_btn" class="btn btn-primary"
                        data-dismiss="modal">{{ trans('file.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- coupon modal -->
    <div id="coupon-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('file.Coupon Code') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" id="coupon-code" class="form-control"
                            placeholder="Type Coupon Code...">
                    </div>
                    <button type="button" class="btn btn-primary coupon-check"
                        data-dismiss="modal">{{ trans('file.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- order_tax modal -->
    <div id="order-tax" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('file.Order Tax') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="order_tax_rate">
                        <select class="form-control" name="order_tax_rate_select" id="order-tax-rate-select">
                            <option value="0">No Tax</option>
                            @foreach ($lims_tax_list as $tax)
                                <option value="{{ $tax->rate }}">{{ $tax->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" name="order_tax_btn" class="btn btn-primary"
                        data-dismiss="modal">{{ trans('file.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- shipping_cost modal -->
    <div id="shipping-cost-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('file.Shipping Cost') }}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                            aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="shipping_cost" class="form-control numkey"
                            id="shipping-cost-val" step="any" onkeyup='saveValue(this);'>
                    </div>
                    <button type="button" name="shipping_cost_btn" class="btn btn-primary"
                        data-dismiss="modal">{{ trans('file.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>
