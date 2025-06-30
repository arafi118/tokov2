@extends('backend.layout.main') @section('content')
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ trans('file.Update Purchase') }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic">
                                <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                            </p>
                            {!! Form::open([
                                'route' => ['purchases.update', $lims_purchase_data->id],
                                'method' => 'put',
                                'files' => true,
                                'id' => 'purchase-form',
                            ]) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Date') }}</label>
                                                <input type="text" name="created_at" class="form-control date"
                                                    value="{{ date($general_setting->date_format, strtotime($lims_purchase_data->created_at->toDateString())) }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Reference No') }}</label>
                                                <p><strong>{{ $lims_purchase_data->reference_no }}</strong> </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 {{ count($lims_warehouse_list) <= 1 ? 'd-none' : '' }}">
                                            <div class="form-group">
                                                <label>{{ trans('file.Warehouse') }} *</label>
                                                <input type="hidden" name="warehouse_id_hidden"
                                                    value="{{ $lims_purchase_data->warehouse_id }}" />
                                                <select required name="warehouse_id" class="selectpicker form-control"
                                                    data-live-search="true" title="Select warehouse...">
                                                    @foreach ($lims_warehouse_list as $warehouse)
                                                        <option
                                                            {{ $lims_purchase_data->warehouse_id == $warehouse->id ? 'selected' : '' }}
                                                            value="{{ $warehouse->id }}">
                                                            {{ $warehouse->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Supplier') }}</label>
                                                <input type="hidden" name="supplier_id_hidden"
                                                    value="{{ $lims_purchase_data->supplier_id }}" />
                                                <select required name="supplier_id" class="selectpicker form-control"
                                                    data-live-search="true" id="supplier-id" title="Select supplier...">
                                                    @foreach ($lims_supplier_list as $supplier)
                                                        <option value="{{ $supplier->id }}">
                                                            {{ $supplier->name . ' (' . $supplier->company_name . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Purchase Status') }}</label>
                                                <input type="hidden" name="status_hidden"
                                                    value="{{ $lims_purchase_data->status }}">
                                                <select name="status" class="form-control">
                                                    <option value="1">{{ trans('file.Recieved') }}</option>
                                                    <!--  <option value="2">{{ trans('file.Partial') }}</option>
                                                                                                                                                                                                                                                                                                                    <option value="3">{{ trans('file.Pending') }}</option> -->
                                                    <option value="4">{{ trans('file.Ordered') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Attach Document') }}</label> <i
                                                    class="dripicons-question" data-toggle="tooltip"
                                                    title="Only jpg, jpeg, png, gif, pdf, csv, docx, xlsx and txt file is supported"></i>
                                                <input type="file" name="document" class="form-control">
                                                @if ($errors->has('extension'))
                                                    <span>
                                                        <strong>{{ $errors->first('extension') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Pre Order</label><br>
                                                {{ $lims_purchase_data->is_po }}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Tempo</label><br>
                                                {{ $lims_purchase_data->is_tempo }}
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label>{{ trans('file.Select Product') }}</label>
                                            <div class="search-box input-group">
                                                <button type="button" class="btn btn-secondary"><i
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
                                                            <th>{{ trans('file.Quantity') }}</th>
                                                            <th class="recieved-product-qty d-none">
                                                                {{ trans('file.Recieved') }}</th>
                                                            <th>{{ trans('file.Batch No') }}</th>
                                                            <th>{{ trans('file.Expired Date') }}</th>
                                                            <th>{{ trans('file.Net Unit Cost') }}</th>
                                                            <th>{{ trans('file.Discount') }}</th>
                                                            <th>{{ trans('file.Cashback') }}</th>
                                                            <th>{{ trans('file.Tax') }}</th>
                                                            <th>{{ trans('file.Subtotal') }}</th>
                                                            <th><i class="dripicons-trash"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $temp_unit_name = [];
                                                        $temp_unit_operator = [];
                                                        $temp_unit_operation_value = [];
                                                        ?>
                                                        @foreach ($lims_product_purchase_data as $product_purchase)
                                                            <tr>
                                                                <?php
                                                                $product_data = DB::connection(env('TENANT_DB_CONNECTION'))->table('products')->find($product_purchase->product_id);
                                                                if ($product_purchase->variant_id) {
                                                                    $product_variant_data = \App\ProductVariant::FindExactProduct($product_data->id, $product_purchase->variant_id)->select('item_code')->first();
                                                                    $product_data->code = $product_variant_data->item_code;
                                                                }
                                                                
                                                                $tax = DB::connection(env('TENANT_DB_CONNECTION'))->table('taxes')->where('rate', $product_purchase->tax_rate)->first();
                                                                
                                                                $units = DB::connection(env('TENANT_DB_CONNECTION'))->table('units')->where('base_unit', $product_data->unit_id)->orWhere('id', $product_data->unit_id)->get();
                                                                
                                                                $unit_name = [];
                                                                $unit_operator = [];
                                                                $unit_operation_value = [];
                                                                
                                                                foreach ($units as $unit) {
                                                                    if ($product_purchase->purchase_unit_id == $unit->id) {
                                                                        array_unshift($unit_name, $unit->unit_name);
                                                                        array_unshift($unit_operator, $unit->operator);
                                                                        array_unshift($unit_operation_value, $unit->operation_value);
                                                                    } else {
                                                                        $unit_name[] = $unit->unit_name;
                                                                        $unit_operator[] = $unit->operator;
                                                                        $unit_operation_value[] = $unit->operation_value;
                                                                    }
                                                                }
                                                                if ($product_data->tax_method == 1) {
                                                                    $product_cost = ($product_purchase->net_unit_cost + $product_purchase->discount / $product_purchase->qty) / $unit_operation_value[0];
                                                                } else {
                                                                    $product_cost = ($product_purchase->total + $product_purchase->discount / $product_purchase->qty) / $product_purchase->qty / $unit_operation_value[0];
                                                                }
                                                                
                                                                $temp_unit_name = $unit_name = implode(',', $unit_name) . ',';
                                                                
                                                                $temp_unit_operator = $unit_operator = implode(',', $unit_operator) . ',';
                                                                
                                                                $temp_unit_operation_value = $unit_operation_value = implode(',', $unit_operation_value) . ',';
                                                                
                                                                $product_batch_data = \App\ProductBatch::select('batch_no', 'expired_date')->find($product_purchase->product_batch_id);
                                                                ?>
                                                                <td>{{ $product_data->name }} <button type="button"
                                                                        class="edit-product btn btn-link"
                                                                        data-toggle="modal" data-target="#editModal"> <i
                                                                            class="dripicons-document-edit"></i></button>
                                                                </td>
                                                                <td>{{ $product_data->code }}</td>
                                                                <td><input type="number" class="form-control qty"
                                                                        name="qty[]" value="{{ $product_purchase->qty }}"
                                                                        step="any" required /></td>
                                                                <td class="recieved-product-qty d-none"><input
                                                                        type="number" class="form-control recieved"
                                                                        name="recieved[]"
                                                                        value="{{ $product_purchase->recieved }}"
                                                                        step="any" /></td>
                                                                @if ($product_purchase->product_batch_id)
                                                                    <td>
                                                                        <input type="hidden" name="product_batch_id[]"
                                                                            value="{{ $product_purchase->product_batch_id }}">
                                                                        <input type="text"
                                                                            class="form-control batch-no"
                                                                            name="batch_no[]"
                                                                            value="{{ $product_batch_data->batch_no }}"
                                                                            required />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                            class="form-control expired-date"
                                                                            name="expired_date[]"
                                                                            value="{{ $product_batch_data->expired_date }}"
                                                                            required />
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="hidden" name="product_batch_id[]">
                                                                        <input type="text"
                                                                            class="form-control batch-no"
                                                                            name="batch_no[]" disabled />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                            class="form-control expired-date"
                                                                            name="expired_date[]" disabled />
                                                                    </td>
                                                                @endif
                                                                <td class="net_unit_cost">
                                                                    {{ number_format((float) $product_purchase->net_unit_cost, 2) }}
                                                                </td>
                                                                <td class="discount">
                                                                    {{ number_format((float) $product_purchase->discount, 2) }}
                                                                </td>
                                                                <td class="cashback">
                                                                    {{ number_format((float) $product_purchase->cashback, 2) }}
                                                                </td>
                                                                <td class="tax">
                                                                    {{ number_format((float) $product_purchase->tax, 2) }}
                                                                </td>
                                                                <td class="sub-total">
                                                                    {{ number_format((float) $product_purchase->total, 2) }}
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="ibtnDel btn btn-sm btn-danger">
                                                                        <i class="dripicons-trash"></i>
                                                                    </button>
                                                                </td>
                                                                <input type="hidden" class="product-id"
                                                                    name="product_id[]"
                                                                    value="{{ $product_data->id }}" />
                                                                <input type="hidden" class="product-code"
                                                                    name="product_code[]"
                                                                    value="{{ $product_data->code }}" />
                                                                <input type="hidden" class="product-cost"
                                                                    name="product_cost[]" value="{{ $product_cost }}" />
                                                                <input type="hidden" class="purchase-unit"
                                                                    name="purchase_unit[]" value="{{ $unit_name }}" />
                                                                <input type="hidden" class="purchase-unit-operator"
                                                                    value="{{ $unit_operator }}" />
                                                                <input type="hidden"
                                                                    class="purchase-unit-operation-value"
                                                                    value="{{ $unit_operation_value }}" />
                                                                <input type="hidden" class="net_unit_cost"
                                                                    name="net_unit_cost[]"
                                                                    value="{{ $product_purchase->net_unit_cost }}" />
                                                                <input type="hidden" class="discount-value"
                                                                    name="discount[]"
                                                                    value="{{ $product_purchase->discount }}" />
                                                                <input type="hidden" class="cashback-value"
                                                                    name="cashback[]"
                                                                    value="{{ $product_purchase->cashback }}" />
                                                                <input type="hidden" class="tax-rate" name="tax_rate[]"
                                                                    value="{{ $product_purchase->tax_rate }}" />
                                                                @if ($tax)
                                                                    <input type="hidden" class="tax-name"
                                                                        value="{{ $tax->name }}" />
                                                                @else
                                                                    <input type="hidden" class="tax-name"
                                                                        value="No Tax" />
                                                                @endif
                                                                <input type="hidden" class="tax-method"
                                                                    value="{{ $product_data->tax_method }}" />
                                                                <input type="hidden" class="tax-value" name="tax[]"
                                                                    value="{{ $product_purchase->tax }}" />
                                                                <input type="hidden" class="subtotal-value"
                                                                    name="subtotal[]"
                                                                    value="{{ $product_purchase->total }}" />
                                                                <input type="hidden" class="imei-number"
                                                                    name="imei_number[]"
                                                                    value="{{ $product_purchase->imei_number }}" />
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="tfoot active">
                                                        <th colspan="2">{{ trans('file.Total') }}</th>
                                                        <th id="total-qty">{{ $lims_purchase_data->total_qty }}</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th class="recieved-product-qty d-none"></th>
                                                        <th id="total-discount">
                                                            {{ number_format((float) $lims_purchase_data->total_discount, 2) }}
                                                        </th>
                                                        <th id="total-cashback">
                                                            {{ number_format((float) $lims_purchase_data->total_cashback, 2) }}
                                                        </th>
                                                        <th id="total-tax">
                                                            {{ number_format((float) $lims_purchase_data->total_tax, 2) }}
                                                        </th>
                                                        <th id="total">
                                                            {{ number_format((float) $lims_purchase_data->total_cost, 2) }}
                                                        </th>
                                                        <th><i class="dripicons-trash"></i></th>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_qty"
                                                    value="{{ $lims_purchase_data->total_qty }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_discount"
                                                    value="{{ $lims_purchase_data->total_discount }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_tax"
                                                    value="{{ $lims_purchase_data->total_tax }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="total_cost"
                                                    value="{{ $lims_purchase_data->total_cost }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="item"
                                                    value="{{ $lims_purchase_data->item }}" />
                                                <input type="hidden" name="order_tax"
                                                    value="{{ $lims_purchase_data->order_tax }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="grand_total"
                                                    value="{{ $lims_purchase_data->grand_total }}" />
                                                <input type="hidden" name="paid_amount"
                                                    value="{{ $lims_purchase_data->paid_amount }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Order Discount Type') }}</label>
                                                        <select id="order-discount-type" name="order_discount_type"
                                                            class="form-control calculate">
                                                            <option
                                                                {{ $lims_purchase_data->order_discount_type == 'Flat' ? 'selected' : '' }}
                                                                value="Flat">
                                                                {{ trans('file.Flat') }}
                                                            </option>
                                                            <option
                                                                {{ $lims_purchase_data->order_discount_type == 'Percentage' ? 'selected' : '' }}
                                                                value="Percentage">
                                                                {{ trans('file.Percentage') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Value') }}</label>
                                                        <input type="text" name="order_discount_value"
                                                            class="form-control calculate decimal" id="order-discount-val"
                                                            value="{{ number_format($lims_purchase_data->order_discount_value, 2) }}">
                                                        <input type="hidden" name="order_discount" class="form-control"
                                                            id="order-discount"
                                                            value="{{ $lims_purchase_data->order_discount }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Order Cashback Type') }}</label>
                                                        <select id="order-cashback-type" name="order_cashback_type"
                                                            class="form-control calculate">
                                                            <option
                                                                {{ $lims_purchase_data->order_cashback_type == 'Flat' ? 'selected' : '' }}
                                                                value="Flat">
                                                                {{ trans('file.Flat') }}
                                                            </option>
                                                            <option
                                                                {{ $lims_purchase_data->order_cashback_type == 'Percentage' ? 'selected' : '' }}
                                                                value="Percentage">
                                                                {{ trans('file.Percentage') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Value') }}</label>
                                                        <input type="text" name="order_cashback_value"
                                                            class="form-control calculate decimal" id="order-cashback-val"
                                                            value="{{ number_format($lims_purchase_data->order_cashback_value, 2) }}">
                                                        <input type="hidden" name="order_cashback" class="form-control"
                                                            id="order-cashback"
                                                            value="{{ $lims_purchase_data->order_cashback }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>{{ trans('file.Order Tax') }}</label>
                                                        <input type="hidden" name="order_tax_rate_hidden"
                                                            value="{{ $lims_purchase_data->order_tax_rate }}">
                                                        <select class="form-control" name="order_tax_rate">
                                                            <option
                                                                {{ $lims_purchase_data->order_tax_rate == 0 ? 'selected' : '' }}
                                                                value="0">
                                                                {{ trans('file.No Tax') }}
                                                            </option>
                                                            @foreach ($lims_tax_list as $tax)
                                                                <option
                                                                    {{ $lims_purchase_data->order_tax_rate == $tax->rate ? 'selected' : '' }}
                                                                    value="{{ $tax->rate }}">
                                                                    {{ $tax->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>
                                                            <strong>{{ trans('file.Shipping Cost') }}</strong>
                                                        </label>
                                                        <input type="number" name="shipping_cost" class="form-control"
                                                            value="{{ $lims_purchase_data->shipping_cost }}"
                                                            step="any" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-flex flex-column">
                                            <div class="form-group form-group flex-grow-1 d-flex flex-column h-100">
                                                <label>{{ trans('file.Note') }}</label>
                                                <textarea class="form-control h-100 flex-grow-1" name="note">{{ $lims_purchase_data->note }}</textarea>
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
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>{{ trans('file.Paying Amount') }} *</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text prepend">Rp</span>
                                                                </div>
                                                                <input type="text" id="amount" name="amount"
                                                                    class="form-control text-right decimal" step="any"
                                                                    required
                                                                    value="{{ number_format($lims_purchase_data->paid_amount, 2) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mt-1">
                                                            <label>{{ trans('file.Paid By') }}</label>
                                                            <select name="paid_by_id" id="paid_by_id_add"
                                                                class="form-control selectpicker">
                                                                <option value="1">Cash</option>
                                                                <option value="5">Debit Card</option>
                                                            </select>
                                                        </div>
                                                        <div id="debit_card" class="col-md-12 mt-1">
                                                            <div class="form-group">
                                                                <label>Transfer / Debit Card</label>
                                                                <select class="form-control selectpicker"
                                                                    name="no_rek_bank">
                                                                    @foreach ($rekening as $rek)
                                                                        <option value="{{ $rek->id }}">
                                                                            {{ $rek->nama }} -
                                                                            {{ $rek->no_rek_bank }} -
                                                                            {{ $rek->atas_nama_rek }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary"
                                                                id="submit-btn">
                                                                {{ trans('file.submit') }}
                                                            </button>
                                                        </div>
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
                                    <input type="number" name="edit_qty" class="form-control" step="any">
                                </div>
                                <?php
                                $tax_name_all[] = 'No Tax';
                                $tax_rate_all[] = 0;
                                foreach ($lims_tax_list as $tax) {
                                    $tax_name_all[] = $tax->name;
                                    $tax_rate_all[] = $tax->rate;
                                }
                                ?>
                                <div class="col-md-4 form-group">
                                    <label>{{ trans('file.Tax Rate') }}</label>
                                    <select name="edit_tax_rate" class="form-control selectpicker">
                                        @foreach ($tax_name_all as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>{{ trans('file.Product Unit') }}</label>
                                    <select name="edit_unit" class="form-control selectpicker">
                                    </select>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label>{{ trans('file.Unit Cost') }}</label>
                                    <input type="number" name="edit_unit_cost" class="form-control" step="any">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>{{ trans('file.Unit Discount') }}</label>
                                    <input type="number" name="edit_discount" class="form-control" step="any">
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label>{{ trans('file.Unit Cashback') }}</label>
                                    <input type="number" name="edit_cashback" class="form-control mask" step="any">
                                </div>
                            </div>
                            <button type="button" name="update_btn"
                                class="btn btn-primary">{{ trans('file.update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        function toDecimal(angka) {
            angka = parseInt(angka);
            let decimal = angka.toLocaleString('en-US', {
                minimumFractionDigits: 2
            });
            return decimal;
        }

        function toNumber(angka) {
            let number = parseFloat(angka.replace(/,/g, ''));
            return number;
        }

        $("ul#purchase").siblings('a').addClass("active");
        $("ul#purchase").addClass("show");
        $("#debit_card").hide();

        // array data depend on warehouse
        var lims_product_array = [];
        var product_code = [];
        var product_name = [];
        var product_qty = [];

        // array data with selection
        var product_cost = [];
        var product_discount = [];
        var product_cashback = [];
        var tax_rate = [];
        var tax_name = [];
        var tax_method = [];
        var unit_name = [];
        var unit_operator = [];
        var unit_operation_value = [];
        var is_imei = [];

        // temporary array
        var temp_unit_name = [];
        var temp_unit_operator = [];
        var temp_unit_operation_value = [];

        var rowindex;
        var customer_group_rate;
        var row_product_cost;

        var rownumber = $('table.order-list tbody tr:last').index();

        for (rowindex = 0; rowindex <= rownumber; rowindex++) {
            product_cost.push(parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                '.product-cost').val()));
            var total_discount = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                '.discount').text());
            var quantity = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val());
            product_discount.push((total_discount / quantity).toFixed(2));
            var total_cashback = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                '.cashback').text());
            var quantity = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val());
            product_cashback.push((total_cashback / quantity).toFixed(2));
            tax_rate.push(parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate')
                .val()));
            tax_name.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-name').val());
            tax_method.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-method').val());
            temp_unit_name = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val()
                .split(',');
            unit_name.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val());
            unit_operator.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                '.purchase-unit-operator').val());
            unit_operation_value.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                '.purchase-unit-operation-value').val());
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val(temp_unit_name[0]);
        }

        $('.selectpicker').selectpicker({
            style: 'btn-link',
        });

        $('[data-toggle="tooltip"]').tooltip();

        //assigning value
        $('select[name="supplier_id"]').val($('input[name="supplier_id_hidden"]').val());
        $('select[name="warehouse_id"]').val($('input[name="warehouse_id_hidden"]').val());
        $('select[name="status"]').val($('input[name="status_hidden"]').val());
        $('select[name="order_tax_rate"]').val($('input[name="order_tax_rate_hidden"]').val());
        $('.selectpicker').selectpicker('refresh');

        $('#item').text($('input[name="item"]').val() + '(' + $('input[name="total_qty"]').val() + ')');
        $('#subtotal').text(toDecimal($('input[name="total_cost"]').val()));
        $('#order_tax').text(parseFloat($('input[name="order_tax"]').val()).toFixed(2));
        if ($('select[name="status"]').val() == 2) {
            $(".recieved-product-qty").removeClass("d-none");

        }

        if (!$('input[name="order_discount"]').val())
            $('input[name="order_discount"]').val('0.00');
        if (!$('input[name="order_cashback"]').val())
            $('input[name="order_cashback"]').val('0.00');

        $('#order_discount').text(toDecimal($('input[name="order_discount"]').val()));
        $('#order_cashback').text(toDecimal($('input[name="order_cashback"]').val()));
        if (!$('input[name="shipping_cost"]').val())
            $('input[name="shipping_cost"]').val('0.00');
        $('#shipping_cost').text(toDecimal($('input[name="shipping_cost"]').val()));
        $('#grand_total').text(toDecimal($('input[name="grand_total"]').val()));

        $('select[name="status"]').on('change', function() {
            if ($('select[name="status"]').val() == 2) {
                $(".recieved-product-qty").removeClass("d-none");
                $(".qty").each(function() {
                    rowindex = $(this).closest('tr').index();
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.recieved').val(
                        $(this).val());
                });

            } else if (($('select[name="status"]').val() == 3) || ($('select[name="status"]').val() == 4)) {
                $(".recieved-product-qty").addClass("d-none");
                $(".recieved").each(function() {
                    $(this).val(0);
                });
            } else {
                $(".recieved-product-qty").addClass("d-none");
                $(".qty").each(function() {
                    rowindex = $(this).closest('tr').index();
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.recieved').val(
                        $(this).val());
                });
            }
        });


        var lims_product_code = [
            @foreach ($lims_product_list_without_variant as $product)
                <?php
                $productArray[] = htmlspecialchars($product->code . '|' . $product->name);
                ?>
            @endforeach
            @foreach ($lims_product_list_with_variant as $product)
                <?php
                $productArray[] = htmlspecialchars($product->item_code . '|' . $product->name);
                ?>
            @endforeach
            <?php
            echo '"' . implode('","', $productArray) . '"';
            ?>
        ];

        var lims_productcodeSearch = $('#lims_productcodeSearch');

        lims_productcodeSearch.autocomplete({
            source: function(request, response) {
                var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
                response($.grep(lims_product_code, function(item) {
                    return matcher.test(item);
                }));
            },
            response: function(event, ui) {
                if (ui.content.length == 1) {
                    var data = ui.content[0].value;
                    $(this).autocomplete("close");
                    productSearch(data);
                };
            },
            select: function(event, ui) {
                var data = ui.item.value;
                productSearch(data);
            }
        });

        $('body').on('focus', ".expired-date", function() {
            $(this).datepicker({
                format: "yyyy-mm-dd",
                startDate: "<?php echo date('Y-m-d', strtotime('+ 1 days')); ?>",
                autoclose: true,
                todayHighlight: true
            });
        });

        //Change quantity
        $("#myTable").on('input', '.qty', function() {
            rowindex = $(this).closest('tr').index();
            if ($(this).val() < 1 && $(this).val() != '') {
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(1);
                alert("Quantity can't be less than 1");
            }
            checkQuantity($(this).val(), true);
        });


        //Delete product
        $("table.order-list tbody").on("click", ".ibtnDel", function(event) {
            rowindex = $(this).closest('tr').index();
            product_cost.splice(rowindex, 1);
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
            $(".imei-section").remove();
            var imeiNumbers = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.imei-number')
                .val();
            if (imeiNumbers || is_imei[rowindex]) {
                htmlText =
                    '<div class="col-md-12 form-group imei-section"><label>IMEI or Serial Numbers</label><input type="text" name="imei_numbers" value="' +
                    imeiNumbers +
                    '" class="form-control imei_number" placeholder="Type imei or serial numbers and separate them by comma. Example:1001,2001" step="any"></div>';
                $("#editModal .modal-element").append(htmlText);
            }
            var row_product_name = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                'td:nth-child(1)').text();
            var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                'td:nth-child(2)').text();
            $('#modal_header').text(row_product_name + '(' + row_product_code + ')');

            var qty = $(this).closest('tr').find('.qty').val();
            $('input[name="edit_qty"]').val(qty);

            $('input[name="edit_discount"]').val(toDecimal(product_discount[rowindex]));
            $('input[name="edit_cashback"]').val(toDecimal(product_cashback[rowindex]));

            unitConversion();
            $('input[name="edit_unit_cost"]').val(row_product_cost.toFixed(2));

            var tax_name_all = <?php echo json_encode($tax_name_all); ?>;
            var pos = tax_name_all.indexOf(tax_name[rowindex]);
            $('select[name="edit_tax_rate"]').val(pos);

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
            $('.selectpicker').selectpicker('refresh');
        });

        //Update product
        $('button[name="update_btn"]').on("click", function() {
            var imeiNumbers = $("#editModal input[name=imei_numbers]").val();
            if (imeiNumbers || is_imei[rowindex]) {
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.imei-number').val(
                    imeiNumbers);
            }

            var edit_discount_value = toNumber($('input[name="edit_discount"]').val());
            var edit_cashback_value = toNumber($('input[name="edit_cashback"]').val());
            var edit_qty = $('input[name="edit_qty"]').val();
            var edit_unit_cost = $('input[name="edit_unit_cost"]').val();

            edit_discount = parseFloat(edit_discount_value);
            edit_cashback = parseFloat(edit_cashback_value);

            if (parseFloat(edit_discount) > parseFloat(edit_unit_cost)) {
                alert('Invalid Discount Input!');
                return;
            }

            if (parseFloat(edit_cashback) > parseFloat(edit_unit_cost)) {
                alert('Invalid Cashback Input!');
                return;
            }

            if (edit_qty < 1) {
                $('input[name="edit_qty"]').val(1);
                edit_qty = 1;
                alert("Quantity can't be less than 1");
            }

            var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(","));
            var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[rowindex]
                .indexOf(","));
            row_unit_operation_value = parseFloat(row_unit_operation_value);
            var tax_rate_all = @json($tax_rate_all);

            tax_rate[rowindex] = parseFloat(tax_rate_all[$('select[name="edit_tax_rate"]').val()]);
            tax_name[rowindex] = $('select[name="edit_tax_rate"] option:selected').text();

            if (row_unit_operator == '*') {
                product_cost[rowindex] = toNumber($('input[name="edit_unit_cost"]').val()) /
                    row_unit_operation_value;
            } else {
                product_cost[rowindex] = toNumber($('input[name="edit_unit_cost"]').val()) *
                    row_unit_operation_value;
            }

            product_discount[rowindex] = edit_discount_value;
            product_cashback[rowindex] = edit_cashback_value;

            var position = $('select[name="edit_unit"]').val();
            var temp_operator = temp_unit_operator[position];
            var temp_operation_value = temp_unit_operation_value[position];
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val(
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
            checkQuantity(edit_qty, false);
        });

        function productSearch(data) {
            $.ajax({
                type: 'GET',
                url: '../lims_product_search',
                data: {
                    data: data
                },
                success: function(data) {
                    var flag = 1;
                    $(".product-code").each(function(i) {
                        if ($(this).val() == data[1]) {
                            rowindex = i;
                            var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex +
                                1) + ') .qty').val()) + 1;
                            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(
                                qty);
                            if ($('select[name="status"]').val() == 1 || $('select[name="status"]')
                                .val() == 1) {
                                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) +
                                    ') .recieved').val(qty);
                            }
                            calculateRowProductData(qty);
                            flag = 0;
                        }
                    });
                    $("input[name='product_code_name']").val('');
                    if (flag) {
                        var newRow = $("<tr>");
                        var cols = '';
                        temp_unit_name = (data[6]).split(',');
                        cols += '<td>' + data[0] +
                            '<button type="button" class="edit-product btn btn-link" data-toggle="modal" data-target="#editModal"> <i class="dripicons-document-edit"></i></button></td>';
                        cols += '<td>' + data[1] + '</td>';
                        cols +=
                            '<td><input type="number" class="form-control qty" name="qty[]" value="1" step="any" required /></td>';
                        if ($('select[name="status"]').val() == 1)
                            cols +=
                            '<td class="recieved-product-qty d-none"><input type="number" class="form-control recieved" name="recieved[]" value="1" step="any" /></td>';
                        else if ($('select[name="status"]').val() == 2)
                            cols +=
                            '<td class="recieved-product-qty"><input type="number" class="form-control recieved" name="recieved[]" value="1" step="any"/></td>';
                        else
                            cols +=
                            '<td class="recieved-product-qty d-none"><input type="number" class="form-control recieved" name="recieved[]" value="0" step="any"/></td>';
                        if (data[10]) {
                            cols +=
                                '<td><input type="text" class="form-control batch-no" name="batch_no[]" required/></td>';
                            cols +=
                                '<td><input type="text" class="form-control expired-date" name="expired_date[]" required/></td>';
                        } else {
                            cols +=
                                '<td><input type="text" class="form-control batch-no" name="batch_no[]" disabled/></td>';
                            cols +=
                                '<td><input type="text" class="form-control expired-date" name="expired_date[]" disabled/></td>';
                        }
                        cols += '<td class="net_unit_cost"></td>';
                        cols += '<td class="discount">0.00</td>';
                        cols += '<td class="tax"></td>';
                        cols += '<td class="sub-total"></td>';
                        cols +=
                            '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{ trans('file.delete') }}</button></td>';
                        cols += '<input type="hidden" class="product-code" name="product_code[]" value="' +
                            data[1] + '"/>';
                        cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[
                            9] + '"/>';
                        cols += '<input type="hidden" class="purchase-unit" name="purchase_unit[]" value="' +
                            temp_unit_name[0] + '"/>';
                        cols += '<input type="hidden" class="net_unit_cost" name="net_unit_cost[]" />';
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
                        product_cost.splice(rowindex, 0, parseFloat(data[2]));
                        product_discount.splice(rowindex, 0, '0.00');
                        product_cashback.splice(rowindex, 0, '0.00');
                        tax_rate.splice(rowindex, 0, parseFloat(data[3]));
                        tax_name.splice(rowindex, 0, data[4]);
                        tax_method.splice(rowindex, 0, data[5]);
                        unit_name.splice(rowindex, 0, data[6]);
                        unit_operator.splice(rowindex, 0, data[7]);
                        unit_operation_value.splice(rowindex, 0, data[8]);
                        is_imei.splice(rowindex, 0, data[11]);
                        calculateRowProductData(1);
                        if (data[11]) {
                            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                                '.edit-product').click();
                        }
                    }
                }
            });
        }

        function checkQuantity(purchase_qty, flag) {
            var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)')
                .text();
            var pos = product_code.indexOf(row_product_code);
            var operator = unit_operator[rowindex].split(',');
            var operation_value = unit_operation_value[rowindex].split(',');
            if (operator[0] == '*')
                total_qty = purchase_qty * operation_value[0];
            else if (operator[0] == '/')
                total_qty = purchase_qty / operation_value[0];

            $('#editModal').modal('hide');
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(purchase_qty);
            var status = $('select[name="status"]').val();
            if (status == '1' || status == '2')
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.recieved').val(purchase_qty);
            else
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.recieved').val(0);

            calculateRowProductData(purchase_qty);
        }

        function calculateRowProductData(quantity) {
            unitConversion();
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount').text((product_discount[
                rowindex] * quantity).toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount-value').val((product_discount[
                rowindex] * quantity).toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.cashback').text((product_cashback[
                rowindex] * quantity).toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.cashback-value').val((product_cashback[
                rowindex] * quantity).toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val(tax_rate[rowindex]
                .toFixed(2));

            if (tax_method[rowindex] == 1) {
                var net_unit_cost = row_product_cost - product_discount[rowindex];
                var tax = net_unit_cost * quantity * (tax_rate[rowindex] / 100);
                var sub_total = (net_unit_cost * quantity) + tax;

                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').text(net_unit_cost
                    .toFixed(2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').val(net_unit_cost
                    .toFixed(2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax').text(tax.toFixed(2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sub-total').text(sub_total.toFixed(
                    2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total
                    .toFixed(2));
            } else {
                var sub_total_unit = row_product_cost - product_discount[rowindex];
                var net_unit_cost = (100 / (100 + tax_rate[rowindex])) * sub_total_unit;
                var tax = (sub_total_unit - net_unit_cost) * quantity;
                var sub_total = sub_total_unit * quantity;

                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').text(net_unit_cost
                    .toFixed(2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').val(net_unit_cost
                    .toFixed(2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax').text(tax.toFixed(2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sub-total').text(sub_total.toFixed(
                    2));
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total
                    .toFixed(2));
            }

            calculateTotal();
        }

        function unitConversion() {
            var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(","));
            var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[rowindex].indexOf(
                ","));
            row_unit_operation_value = parseFloat(row_unit_operation_value);
            if (row_unit_operator == '*') {
                row_product_cost = product_cost[rowindex] * row_unit_operation_value;
            } else {
                row_product_cost = product_cost[rowindex] / row_unit_operation_value;
            }
        }

        function calculateTotal() {
            //Sum of quantity
            var total_qty = 0;
            $(".qty").each(function() {

                if ($(this).val() == '') {
                    total_qty += 0;
                } else {
                    total_qty += toNumber($(this).val());
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
            $('input[name="total_discount"]').val(total_discount);

            //Sum of tax
            var total_tax = 0;
            $(".tax").each(function() {
                total_tax += toNumber($(this).text());
            });
            $("#total-tax").text(toDecimal(total_tax));
            $('input[name="total_tax"]').val(total_tax);

            //Sum of subtotal
            var total = 0;
            $(".sub-total").each(function() {
                total += toNumber($(this).text());
            });
            $("#total").text(toDecimal(total));
            $('input[name="total_cost"]').val(total);

            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            var item = $('table.order-list tbody tr:last').index();

            var total_qty = toNumber($('#total-qty').text());
            var subtotal = toNumber($('#total').text());
            var order_tax = parseFloat($('select[name="order_tax_rate"]').val());
            var order_discount = $('input[name="order_discount"]').val();
            var order_cashback = $('input[name="order_cashback"]').val();
            var order_discount_type = $('select[name="order_discount_type"]').val();
            var order_discount_value = toNumber($('input[name="order_discount_value"]').val());
            var order_cashback_type = $('select[name="order_cashback_type"]').val();
            var order_cashback_value = toNumber($('input[name="order_cashback_value"]').val());
            var shipping_cost = parseFloat($('input[name="shipping_cost"]').val());

            if (!order_discount)
                order_discount = 0.00;
            if (!order_cashback)
                order_cashback = 0.00;
            if (!shipping_cost)
                shipping_cost = 0.00;

            if (order_discount_type == 'Percentage' && order_discount_value > 0) {
                order_discount = (subtotal / 100) * order_discount_value;
            } else {
                order_discount = order_discount_value;
            }

            if (order_cashback_type == 'Percentage' && order_cashback_value > 0) {
                order_cashback = (subtotal / 100) * order_cashback_value;
            } else {
                order_cashback = order_cashback_value;
            }

            console.log(order_discount)
            item = ++item + '(' + total_qty + ')';
            order_tax = (subtotal - order_discount) * (order_tax / 100);
            var grand_total = (subtotal + order_tax + shipping_cost) - order_discount;

            $('#item').text(item);
            $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
            $('#subtotal').text(toDecimal(subtotal));
            $('#order_tax').text(order_tax.toFixed(2));
            $('input[name="order_tax"]').val(order_tax.toFixed(2));
            $('#order_discount').text(toDecimal(order_discount));
            $('#order_cashback').text(toDecimal(order_cashback));
            $('#shipping_cost').text(toDecimal(shipping_cost));
            $('#grand_total').text(toDecimal(grand_total));
            $('input[name="grand_total"]').val(grand_total);
        }

        $('.calculate').on("change", function() {
            calculateGrandTotal();
        });

        $('input[name="order_discount"]').on("input", function() {
            calculateGrandTotal();
        });

        $('input[name="order_cashback"]').on("input", function() {
            calculateGrandTotal();
        });

        $('input[name="shipping_cost"]').on("input", function() {
            calculateGrandTotal();
        });

        $('select[name="order_tax_rate"]').on("change", function() {
            calculateGrandTotal();
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

        $('#purchase-form').on('submit', function(e) {
            var rownumber = $('table.order-list tbody tr:last').index();
            if (rownumber < 0) {
                alert("Please insert product to order table!")
                e.preventDefault();
            } else if ($('select[name="status"]').val() != 1) {
                flag = 0;
                $(".qty").each(function() {
                    rowindex = $(this).closest('tr').index();
                    quantity = $(this).val();
                    recieved = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                        '.recieved').val();

                    if (quantity != recieved) {
                        flag = 1;
                        return false;
                    }
                });
                if (!flag) {
                    alert('Quantity and Recieved value is same! Please Change Purchase Status or Recieved value');
                    e.preventDefault();
                } else
                    $(".batch-no, .expired-date").prop('disabled', false);
            } else {
                $("#submit-button").prop('disabled', true);
                $(".batch-no, .expired-date").prop('disabled', false);
            }
        });

        $('select[name="paid_by_id"]').on("change", function() {
            var id = $(this).val();

            if (id == 5) {
                $("#debit_card").show();
            } else {
                $("#debit_card").hide();
            }
        });

        $('input[name="amount"]').on('keyup', function() {
            var amount = toNumber($(this).val());
            var grand_total = toNumber($('#grand_total').text());
            var change = grand_total - amount;

            if (change < 0) {
                $('#submit-btn').prop('disabled', true);
            } else {
                $('#submit-btn').prop('disabled', false);
            }

            $('input[name="payment_status"]').val('1')
            if (change == '0') {
                $('input[name="payment_status"]').val('2')
            }

            $('input[name="paid_amount"]').val(amount);
        })
    </script>
@endpush
