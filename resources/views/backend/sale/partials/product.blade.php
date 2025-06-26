<div class="col-md-5 d-none d-md-block" id="salepos-product">
    <!-- navbar-->
    <header>
        <nav class="navbar">
            <a href="/sales" class="menu-btn btn-sm"><i class="dripicons-home"> </i></a>

            <div class="navbar-header">
                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                    <li class="nav-item">
                        <a id="btnFullscreen" data-toggle="tooltip" title="Full Screen">
                            <i class="dripicons-expand"></i>
                        </a>
                    </li>
                    @if ($pos_setting_active)
                        <li class="nav-item">
                            <a class="dropdown-item" data-toggle="tooltip" href="{{ route('setting.pos') }}"
                                title="{{ trans('file.POS Setting') }}">
                                <i class="dripicons-gear"></i>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('sales.printLastReciept') }}" data-toggle="tooltip"
                            title="{{ trans('file.Print Last Reciept') }}">
                            <i class="dripicons-print"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" id="register-details-btn" data-toggle="tooltip"
                            title="{{ trans('file.Cash Register Details') }}">
                            <i class="dripicons-briefcase"></i>
                        </a>
                    </li>

                    @if ($today_sale_active)
                        <li class="nav-item">
                            <a href="" id="today-sale-btn" data-toggle="tooltip"
                                title="{{ trans('file.Today Sale') }}">
                                <i class="dripicons-shopping-bag"></i>
                            </a>
                        </li>
                    @endif
                    @if ($today_profit_active)
                        <li class="nav-item">
                            <a href="" id="today-profit-btn" data-toggle="tooltip"
                                title="{{ trans('file.Today Profit') }}">
                                <i class="dripicons-graph-line"></i>
                            </a>
                        </li>
                    @endif
                    @if ($alert_product + count(\Auth::user()->unreadNotifications) > 0)
                        <li class="nav-item" id="notification-icon">
                            <a rel="nofollow" data-toggle="tooltip" title="{{ __('Notifications') }}"
                                class="nav-link dropdown-item">
                                <i class="dripicons-bell"></i>
                                <span
                                    class="badge badge-danger notification-number">{{ $alert_product + count(\Auth::user()->unreadNotifications) }}</span>
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </a>
                            <ul class="right-sidebar" user="menu">
                                <li class="notifications">
                                    <a href="{{ route('report.qtyAlert') }}" class="btn btn-link">{{ $alert_product }}
                                        product exceeds alert
                                        quantity
                                    </a>
                                </li>
                                @foreach (\Auth::user()->unreadNotifications as $key => $notification)
                                    <li class="notifications">
                                        <a href="#" class="btn btn-link">{{ $notification->data['message'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a rel="nofollow" data-toggle="tooltip" class="nav-link dropdown-item">
                            <i class="dripicons-user"></i>
                            <span>{{ ucfirst(Auth::user()->name) }}</span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="right-sidebar">
                            <li>
                                <a href="{{ route('user.profile', ['id' => Auth::id()]) }}">
                                    <i class="dripicons-user"></i> {{ trans('file.profile') }}
                                </a>
                            </li>
                            @if ($general_setting_active)
                                <li>
                                    <a href="{{ route('setting.general') }}">
                                        <i class="dripicons-gear"></i> {{ trans('file.settings') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ url('my-transactions/' . date('Y') . '/' . date('m')) }}">
                                    <i class="dripicons-swap"></i> {{ trans('file.My Transaction') }}
                                </a>
                            </li>
                            @if (Auth::user()->role_id != 5)
                                <li>
                                    <a href="{{ url('holidays/my-holiday/' . date('Y') . '/' . date('m')) }}">
                                        <i class="dripicons-vibrate"></i> {{ trans('file.My Holiday') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                    <i class="dripicons-power"></i> {{ trans('file.logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="filter-window">
        <div class="category mt-3">
            <div class="row ml-2 mr-2 px-2">
                <div class="col-7">Choose category</div>
                <div class="col-5 text-right">
                    <span class="btn btn-default btn-sm">
                        <i class="dripicons-cross"></i>
                    </span>
                </div>
            </div>
            <div class="row ml-2 mt-3">
                @foreach ($lims_category_list as $category)
                    <div class="col-md-3 category-img text-center" data-category="{{ $category->id }}">
                        @if ($category->image)
                            <img src="{{ url('public/images/category', $category->image) }}" />
                        @else
                            <img src="{{ url('/images/product/no_image.png') }}" />
                        @endif
                        <p class="text-center">{{ $category->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="brand mt-3">
            <div class="row ml-2 mr-2 px-2">
                <div class="col-7">Choose brand</div>
                <div class="col-5 text-right">
                    <span class="btn btn-default btn-sm">
                        <i class="dripicons-cross"></i>
                    </span>
                </div>
            </div>
            <div class="row ml-2 mt-3">
                @foreach ($lims_brand_list as $brand)
                    @if ($brand->image)
                        <div class="col-md-3 brand-img text-center" data-brand="{{ $brand->id }}">
                            <img src="{{ url('public/images/brand', $brand->image) }}" />
                            <p class="text-center">{{ $brand->title }}</p>
                        </div>
                    @else
                        <div class="col-md-3 brand-img" data-brand="{{ $brand->id }}">
                            <img src="{{ url('/images/product/zummXD2dvAtI.png') }}" />
                            <p class="text-center">{{ $brand->title }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <button class="btn btn-block btn-sm btn-warning" id="all-filter">
                {{ trans('file.All') }}
            </button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-block btn-sm btn-primary" id="category-filter">
                {{ trans('file.category') }}
            </button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-block btn-sm btn-info" id="brand-filter">
                {{ trans('file.Brand') }}
            </button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-block btn-sm btn-danger" id="featured-filter">{{ trans('file.Featured') }}
            </button>
        </div>
        <div class="col-md-12 table-container pt-2">
            <table id="product-table" class="table no-shadow product-list">
                <thead class="d-none">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < ceil($product_number / 4); $i++)
                        <tr>
                            <td class="product-img sound-btn" title="{{ $lims_product_list[0 + $i * 4]->name }}"
                                data-qty="{{ $lims_product_list[0 + $i * 4]->qty }}"
                                data-product="{{ $lims_product_list[0 + $i * 4]->code . ' (' . $lims_product_list[0 + $i * 4]->name . ')' }}">
                                <img src="{{ url('/images/product', $lims_product_list[0 + $i * 4]->base_image) }}"
                                    width="100%" />
                                <p>{{ $lims_product_list[0 + $i * 4]->name }}</p>
                                <span>{{ $lims_product_list[0 + $i * 4]->code }}</span>
                            </td>
                            @if (!empty($lims_product_list[1 + $i * 4]))
                                <td class="product-img sound-btn" title="{{ $lims_product_list[1 + $i * 4]->name }}"
                                    data-qty="{{ $lims_product_list[1 + $i * 4]->qty }}"
                                    data-product="{{ $lims_product_list[1 + $i * 4]->code . ' (' . $lims_product_list[1 + $i * 4]->name . ')' }}">
                                    <img src="{{ url('/images/product', $lims_product_list[1 + $i * 4]->base_image) }}"
                                        width="100%" />
                                    <p>{{ $lims_product_list[1 + $i * 4]->name }}</p>
                                    <span>{{ $lims_product_list[1 + $i * 4]->code }}</span>
                                </td>
                            @else
                                <td style="border:none;"></td>
                            @endif
                            @if (!empty($lims_product_list[2 + $i * 4]))
                                <td class="product-img sound-btn" title="{{ $lims_product_list[2 + $i * 4]->name }}"
                                    data-qty="{{ $lims_product_list[2 + $i * 4]->qty }}"
                                    data-product="{{ $lims_product_list[2 + $i * 4]->code . ' (' . $lims_product_list[2 + $i * 4]->name . ')' }}">
                                    <img src="{{ url('/images/product', $lims_product_list[2 + $i * 4]->base_image) }}"
                                        width="100%" />
                                    <p>{{ $lims_product_list[2 + $i * 4]->name }}</p>
                                    <span>{{ $lims_product_list[2 + $i * 4]->code }}</span>
                                </td>
                            @else
                                <td style="border:none;"></td>
                            @endif
                            @if (!empty($lims_product_list[3 + $i * 4]))
                                <td class="product-img sound-btn" title="{{ $lims_product_list[3 + $i * 4]->name }}"
                                    data-qty="{{ $lims_product_list[3 + $i * 4]->qty }}"
                                    data-product="{{ $lims_product_list[3 + $i * 4]->code . ' (' . $lims_product_list[3 + $i * 4]->name . ')' }}">
                                    <img src="{{ url('/images/product', $lims_product_list[3 + $i * 4]->base_image) }}"
                                        width="100%" />
                                    <p>{{ $lims_product_list[3 + $i * 4]->name }}</p>
                                    <span>{{ $lims_product_list[3 + $i * 4]->code }}</span>
                                </td>
                            @else
                                <td style="border:none;"></td>
                            @endif
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- product edit modal -->
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
                            <input type="text" name="edit_qty" class="form-control numkey">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{ trans('file.Unit Discount') }}</label>
                            <input type="text" name="edit_discount" class="form-control numkey">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{ trans('file.Unit Cashback') }}</label>
                            <input type="text" name="edit_cashback" class="form-control numkey">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ trans('file.Unit Price') }}</label>
                            <input type="text" name="edit_unit_price" class="form-control numkey" step="any">
                        </div>

                        <div class="col-md-6 form-group">
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
                    <button type="button" name="update_btn"
                        class="btn btn-primary">{{ trans('file.update') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- add customer modal -->
<div id="addCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => 'customer.store', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Customer') }}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <p class="italic">
                    <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                </p>
                <div class="form-group">
                    <label>{{ trans('file.Customer Group') }} *</strong> </label>
                    <select required class="form-control selectpicker" name="customer_group_id">
                        @foreach ($lims_customer_group_all as $customer_group)
                            <option value="{{ $customer_group->id }}">{{ $customer_group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ trans('file.name') }} *</strong> </label>
                    <input type="text" name="customer_name" required class="form-control">
                </div>
                <div class="form-group">
                    <label>{{ trans('file.Email') }}</label>
                    <input type="text" name="email" placeholder="example@example.com" class="form-control">
                </div>
                <div class="form-group">
                    <label>{{ trans('file.Phone Number') }} *</label>
                    <input type="text" name="phone_number" required class="form-control">
                </div>
                <div class="form-group">
                    <label>{{ trans('file.Address') }} *</label>
                    <input type="text" name="address" required class="form-control">
                </div>
                <div class="form-group">
                    <label>{{ trans('file.City') }} *</label>
                    <input type="text" name="city" required class="form-control">
                </div>
                <div class="form-group">
                    <input type="hidden" name="pos" value="1">
                    <input type="submit" value="{{ trans('file.submit') }}" class="btn btn-primary">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<!-- recent transaction modal -->
<div id="recentTransaction" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Recent Transaction') }}
                    <div class="badge badge-primary">{{ trans('file.latest') }} 10</div>
                </h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#sale-latest" role="tab"
                            data-toggle="tab">{{ trans('file.Sale') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#draft-latest" role="tab"
                            data-toggle="tab">{{ trans('file.Draft') }}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane show active" id="sale-latest">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ trans('file.date') }}</th>
                                        <th>{{ trans('file.reference') }}</th>
                                        <th>{{ trans('file.customer') }}</th>
                                        <th>{{ trans('file.grand total') }}</th>
                                        <th>{{ trans('file.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recent_sale as $sale)
                                        @php
                                            $customer = $sale->customer;
                                        @endphp
                                        <tr>
                                            <td>{{ date('d-m-Y', strtotime($sale->created_at)) }}</td>
                                            <td>{{ $sale->reference_no }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $sale->grand_total }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    @if (in_array('sales-edit', $all_permission))
                                                        <a href="{{ route('sales.edit', $sale->id) }}"
                                                            class="btn btn-success btn-sm" title="Edit"><i
                                                                class="dripicons-document-edit"></i></a>&nbsp;
                                                    @endif
                                                    @if (in_array('sales-delete', $all_permission))
                                                        {{ Form::open(['route' => ['sales.destroy', $sale->id], 'method' => 'DELETE']) }}
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirmDelete()" title="Delete"><i
                                                                class="dripicons-trash"></i></button>
                                                        {{ Form::close() }}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="draft-latest">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ trans('file.date') }}</th>
                                        <th>{{ trans('file.reference') }}</th>
                                        <th>{{ trans('file.customer') }}</th>
                                        <th>{{ trans('file.grand total') }}</th>
                                        <th>{{ trans('file.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recent_draft as $draft)
                                        @php
                                            $customer = $draft->customer;
                                        @endphp
                                        <tr>
                                            <td>{{ date('d-m-Y', strtotime($draft->created_at)) }}</td>
                                            <td>{{ $draft->reference_no }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $draft->grand_total }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    @if (in_array('sales-edit', $all_permission))
                                                        <a href="{{ url('sales/' . $draft->id . '/create') }}"
                                                            class="btn btn-success btn-sm" title="Edit"><i
                                                                class="dripicons-document-edit"></i></a>&nbsp;
                                                    @endif
                                                    @if (in_array('sales-delete', $all_permission))
                                                        {{ Form::open(['route' => ['sales.destroy', $draft->id], 'method' => 'DELETE']) }}
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirmDelete()" title="Delete"><i
                                                                class="dripicons-trash"></i></button>
                                                        {{ Form::close() }}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- add cash register modal -->
<div id="cash-register-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['route' => 'cashRegister.store', 'method' => 'post']) !!}
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Cash Register') }}
                </h5>
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

<!-- cash register details modal -->
<div id="register-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">
                    {{ trans('file.Cash Register Details') }}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <p>{{ trans('file.Please review the transaction and payments.') }}</p>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>{{ trans('file.Cash in Hand') }}:</td>
                                    <td id="cash_in_hand" class="text-right">0</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Total Sale Amount') }}:</td>
                                    <td id="total_sale_amount" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Total Payment') }}:</td>
                                    <td id="total_payment" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Cash Payment') }}:</td>
                                    <td id="cash_payment" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Credit Card Payment') }}:</td>
                                    <td id="credit_card_payment" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Cheque Payment') }}:</td>
                                    <td id="cheque_payment" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Gift Card Payment') }}:</td>
                                    <td id="gift_card_payment" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Deposit Payment') }}:</td>
                                    <td id="deposit_payment" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Paypal Payment') }}:</td>
                                    <td id="paypal_payment" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Total Sale Return') }}:</td>
                                    <td id="total_sale_return" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Total Expense') }}:</td>
                                    <td id="total_expense" class="text-right"></td>
                                </tr>
                                <tr>
                                    <td><strong>{{ trans('file.Total Cash') }}:</strong></td>
                                    <td id="total_cash" class="text-right"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6" id="closing-section">
                        <form action="{{ route('cashRegister.close') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cash_register_id">
                            <button type="submit"
                                class="btn btn-primary">{{ trans('file.Close Register') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- today sale modal -->
<div id="today-sale-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Today Sale') }}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <p>{{ trans('file.Please review the transaction and payments.') }}</p>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>{{ trans('file.Total Sale Amount') }}:</td>
                                    <td class="total_sale_amount text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Cash Payment') }}:</td>
                                    <td class="cash_payment text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Credit Card Payment') }}:</td>
                                    <td class="credit_card_payment text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Cheque Payment') }}:</td>
                                    <td class="cheque_payment text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Gift Card Payment') }}:</td>
                                    <td class="gift_card_payment text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Deposit Payment') }}:</td>
                                    <td class="deposit_payment text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Paypal Payment') }}:</td>
                                    <td class="paypal_payment text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Total Payment') }}:</td>
                                    <td class="total_payment text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Total Sale Return') }}:</td>
                                    <td class="total_sale_return text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Total Expense') }}:</td>
                                    <td class="total_expense text-right"></td>
                                </tr>
                                <tr>
                                    <td><strong>{{ trans('file.Total Cash') }}:</strong></td>
                                    <td class="total_cash text-right"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- today profit modal -->
<div id="today-profit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Today Profit') }}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <select required name="warehouseId" class="form-control">
                            <option value="0">{{ trans('file.All Warehouse') }}</option>
                            @foreach ($lims_warehouse_list as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mt-2">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>{{ trans('file.Product Revenue') }}:</td>
                                    <td class="product_revenue text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Product Cost') }}:</td>
                                    <td class="product_cost text-right"></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('file.Expense') }}:</td>
                                    <td class="expense_amount text-right"></td>
                                </tr>
                                <tr>
                                    <td><strong>{{ trans('file.Profit') }}:</strong></td>
                                    <td class="profit text-right"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
