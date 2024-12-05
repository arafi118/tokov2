<nav class="side-navbar shrink">
    <span class="brand-big">
        @if ($general_setting->site_logo)
            <a href="{{ url('/') }}">
                <img src="{{ url('public/logo', $general_setting->site_logo) }}" width="115">
            </a>
        @else
            <a href="{{ url('/') }}">
                <h1 class="d-inline">{{ $general_setting->site_title }}</h1>
            </a>
        @endif
    </span>

    <ul id="side-main-menu" class="side-menu list-unstyled">
        <li>
            <a href="{{ url('/') }}">
                <i class="dripicons-meter"></i>
                <span>{{ __('file.dashboard') }}</span>
            </a>
        </li>
        <li>
            <a href="#product" aria-expanded="false" data-toggle="collapse">
                <i class="dripicons-list"></i>
                <span>{{ __('file.product') }}</span>
            </a>
            <ul id="product" class="collapse list-unstyled ">
                @if ($unit_active)
                    <li id="unit-menu">
                        <a href="{{ route('unit.index') }}">{{ trans('file.Unit') }}</a>
                    </li>
                @endif
                @if ($brand_active)
                    <li id="brand-menu">
                        <a href="{{ route('brand.index') }}">{{ trans('file.Brand') }}</a>
                    </li>
                @endif
                <li id="category-menu">
                    <a href="{{ route('category.index') }}">{{ __('file.category') }}</a>
                </li>
                @if ($warehouse_active)
                    <li id="warehouse-menu">
                        <a href="{{ route('warehouse.index') }}">{{ trans('file.Warehouse') }}</a>
                    </li>
                @endif
                @if ($purchases_index_active)
                    <li id="product-list-menu">
                        <a href="{{ route('products.index') }}">{{ __('file.product_list') }}</a>
                    </li>
                    @if ($products_add_active)
                        <li id="product-create-menu">
                            <a href="{{ route('products.create') }}">{{ __('file.add_product') }}</a>
                        </li>
                    @endif
                @endif
                @if ($print_barcode_active)
                    <li id="printBarcode-menu">
                        <a href="{{ route('product.printBarcode') }}">{{ __('file.print_barcode') }}</a>
                    </li>
                @endif
                @if ($adjustment_active)
                    <li id="adjustment-list-menu">
                        <a href="{{ route('qty_adjustment.index') }}">{{ trans('file.Adjustment List') }}</a>
                    </li>
                    <li id="adjustment-create-menu">
                        <a href="{{ route('qty_adjustment.create') }}">{{ trans('file.Add Adjustment') }}</a>
                    </li>
                @endif
                @if ($stock_count_active)
                    <li id="stock-count-menu">
                        <a href="{{ route('stock-count.index') }}">{{ trans('file.Stock Count') }}</a>
                    </li>
                @endif
            </ul>
        </li>

        @if ($purchases_index_active)
            <li>
                <a href="#purchase" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-card"></i>
                    <span>{{ trans('file.Purchase') }}</span>
                </a>
                <ul id="purchase" class="collapse list-unstyled ">
                    <li id="purchase-list-menu">
                        <a href="{{ route('purchases.index') }}">{{ trans('file.Purchase List') }}</a>
                    </li>
                    @if ($purchases_add_active)
                        <li id="purchase-create-menu">
                            <a href="{{ route('purchases.create') }}">{{ trans('file.Add Purchase') }}</a>
                        </li>
                        <li id="purchase-import-menu">
                            <a href="{{ url('purchases/purchase_by_csv') }}">
                                {{ trans('file.Import Purchase By CSV') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <li>
            <a href="#sale" aria-expanded="false" data-toggle="collapse">
                <i class="dripicons-cart"></i>
                <span>{{ trans('file.Sale') }}</span>
            </a>
            <ul id="sale" class="collapse list-unstyled ">
                @if ($sales_index_active)
                    <li id="sale-list-menu">
                        <a href="{{ route('sales.index') }}">{{ trans('file.Sale List') }}</a>
                    </li>
                    @if ($sales_add_active)
                        <li>
                            <a href="{{ route('sale.pos') }}">POS</a>
                        </li>
                        <li id="sale-create-menu">
                            <a href="{{ route('sales.create') }}">{{ trans('file.Add Sale') }}</a>
                        </li>
                        <li id="sale-import-menu">
                            <a href="{{ url('sales/sale_by_csv') }}">{{ trans('file.Import Sale By CSV') }}</a>
                        </li>
                    @endif
                @endif
                @if ($gift_card_active)
                    <li id="gift-card-menu">
                        <a href="{{ route('gift_cards.index') }}">{{ trans('file.Gift Card List') }}</a>
                    </li>
                @endif
                @if ($coupon_active)
                    <li id="coupon-menu">
                        <a href="{{ route('coupons.index') }}">{{ trans('file.Coupon List') }}</a>
                    </li>
                @endif
                <li id="delivery-menu">
                    <a href="{{ route('delivery.index') }}">{{ trans('file.Delivery List') }}</a>
                </li>
            </ul>
        </li>

        @if ($expenses_index_active)
            <li>
                <a href="#expense" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-wallet"></i>
                    <span>{{ trans('file.Expense') }}</span>
                </a>
                <ul id="expense" class="collapse list-unstyled ">
                    <li id="exp-cat-menu">
                        <a href="{{ route('expense_categories.index') }}">{{ trans('file.Expense Category') }}</a>
                    </li>
                    <li id="exp-list-menu">
                        <a href="{{ route('expenses.index') }}">{{ trans('file.Expense List') }}</a>
                    </li>

                    @if ($expenses_add_active)
                        <li>
                            <a id="add-expense" href=""> {{ trans('file.Add Expense') }}</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if ($quotes_index_active)
            <li>
                <a href="#quotation" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-document"></i><span>{{ trans('file.Quotation') }}</span>
                </a>
                <ul id="quotation" class="collapse list-unstyled ">
                    <li id="quotation-list-menu">
                        <a href="{{ route('quotations.index') }}">{{ trans('file.Quotation List') }}</a>
                    </li>
                    @if ($quotes_add_active)
                        <li id="quotation-create-menu">
                            <a href="{{ route('quotations.create') }}">{{ trans('file.Add Quotation') }}</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if ($transfers_index_active)
            <li>
                <a href="#transfer" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-export"></i>
                    <span>{{ trans('file.Transfer') }}</span>
                </a>
                <ul id="transfer" class="collapse list-unstyled ">
                    <li id="transfer-list-menu">
                        <a href="{{ route('transfers.index') }}">{{ trans('file.Transfer List') }}</a>
                    </li>
                    @if ($transfers_add_active)
                        <li id="transfer-create-menu">
                            <a href="{{ route('transfers.create') }}">{{ trans('file.Add Transfer') }}</a>
                        </li>
                        <li id="transfer-import-menu">
                            <a href="{{ url('transfers/transfer_by_csv') }}">
                                {{ trans('file.Import Transfer By CSV') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <li>
            <a href="#return" aria-expanded="false" data-toggle="collapse">
                <i class="dripicons-return"></i>
                <span>{{ trans('file.return') }}</span>
            </a>
            <ul id="return" class="collapse list-unstyled ">
                @if ($returns_index_active)
                    <li id="sale-return-menu">
                        <a href="{{ route('return-sale.index') }}">{{ trans('file.Sale') }}</a>
                    </li>
                @endif
                @if ($purchase_return_index_active)
                    <li id="purchase-return-menu">
                        <a href="{{ route('return-purchase.index') }}">{{ trans('file.Purchase') }}</a>
                    </li>
                @endif
            </ul>
        </li>

        @if ($account_index_active || $balance_sheet_active || $account_statement_active)
            <li class="">
                <a href="#account" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-briefcase"></i>
                    <span>{{ trans('file.Accounting') }}</span>
                </a>
                <ul id="account" class="collapse list-unstyled ">
                    @if ($account_index_active)
                        <li id="account-list-menu">
                            <a href="{{ route('accounts.index') }}">{{ trans('file.Account List') }}</a>
                        </li>
                        <li>
                            <a id="add-account" href="">{{ trans('file.Add Account') }}</a>
                        </li>
                    @endif
                    @if ($account_journal_active)
                        <li id="jurnal-menu">
                            <a href="{{ route('jurnal.index') }}">
                                {{ trans('file.Journal') }}
                            </a>
                        </li>
                    @endif
                    @if ($account_report_active)
                        <li id="laporan-keuangan-menu">
                            <a href="{{ route('laporan_keuangan') }}">
                                {{ trans('file.Financial Statements') }}
                            </a>
                        </li>
                    @endif
                    @if ($account_close_statement_active)
                        <li id="tutup-buku-menu">
                            <a href="{{ route('tutup_buku.index') }}">
                                {{ trans('file.Close the book') }}
                            </a>
                        </li>
                    @endif
                    @if ($money_transfer_active)
                        <li id="money-transfer-menu">
                            <a href="{{ route('money-transfers.index') }}">{{ trans('file.Money Transfer') }}</a>
                        </li>
                    @endif
                    @if ($balance_sheet_active)
                        <li id="balance-sheet-menu">
                            <a href="{{ route('accounts.balancesheet') }}">{{ trans('file.Balance Sheet') }}</a>
                        </li>
                    @endif
                    @if ($account_statement_active)
                        <li id="account-statement-menu">
                            <a id="account-statement" href="">{{ trans('file.Account Statement') }}</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <li class="">
            <a href="#hrm" aria-expanded="false" data-toggle="collapse">
                <i class="dripicons-user-group"></i>
                <span>HRM</span>
            </a>
            <ul id="hrm" class="collapse list-unstyled ">
                @if ($department_active)
                    <li id="dept-menu">
                        <a href="{{ route('departments.index') }}">{{ trans('file.Department') }}</a>
                    </li>
                @endif
                @if ($employees_index_active)
                    <li id="employee-menu">
                        <a href="{{ route('employees.index') }}">{{ trans('file.Employee') }}</a>
                    </li>
                @endif
                @if ($attendance_active)
                    <li id="attendance-menu">
                        <a href="{{ route('attendance.index') }}">{{ trans('file.Attendance') }}</a>
                    </li>
                @endif
                @if ($payroll_active)
                    <li id="payroll-menu">
                        <a href="{{ route('payroll.index') }}">{{ trans('file.Payroll') }}</a>
                    </li>
                @endif
                <li id="holiday-menu">
                    <a href="{{ route('holidays.index') }}">{{ trans('file.Holiday') }}</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#people" aria-expanded="false" data-toggle="collapse">
                <i class="dripicons-user"></i>
                <span>{{ trans('file.People') }}</span>
            </a>
            <ul id="people" class="collapse list-unstyled ">
                @if ($users_index_active)
                    <li id="user-list-menu">
                        <a href="{{ route('user.index') }}">{{ trans('file.User List') }}</a>
                    </li>
                    @if ($users_add_active)
                        <li id="user-create-menu">
                            <a href="{{ route('user.create') }}">{{ trans('file.Add User') }}</a>
                        </li>
                    @endif
                @endif
                @if ($customers_index_active)
                    <li id="customer-list-menu">
                        <a href="{{ route('customer.index') }}">{{ trans('file.Customer List') }}</a>
                    </li>
                    @if ($customers_add_active)
                        <li id="customer-create-menu">
                            <a href="{{ route('customer.create') }}">{{ trans('file.Add Customer') }}</a>
                        </li>
                    @endif
                @endif
                @if ($billers_index_active)
                    <li id="biller-list-menu">
                        <a href="{{ route('biller.index') }}">{{ trans('file.Biller List') }}</a>
                    </li>
                    @if ($billers_add_active)
                        <li id="biller-create-menu">
                            <a href="{{ route('biller.create') }}">{{ trans('file.Add Biller') }}</a>
                        </li>
                    @endif
                @endif
                @if ($suppliers_index_active)
                    <li id="supplier-list-menu">
                        <a href="{{ route('supplier.index') }}">{{ trans('file.Supplier List') }}</a>
                    </li>
                    @if ($suppliers_add_active)
                        <li id="supplier-create-menu">
                            <a href="{{ route('supplier.create') }}">{{ trans('file.Add Supplier') }}</a>
                        </li>
                    @endif
                @endif
            </ul>
        </li>

        <li>
            <a href="#report" aria-expanded="false" data-toggle="collapse">
                <i class="dripicons-document-remove"></i>
                <span>{{ trans('file.Reports') }}</span>
            </a>
            <ul id="report" class="collapse list-unstyled ">
                @if ($profit_loss_active)
                    <li id="profit-loss-report-menu">
                        {!! Form::open(['route' => 'report.profitLoss', 'method' => 'post', 'id' => 'profitLoss-report-form']) !!}
                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                        <a id="profitLoss-link" href="">{{ trans('file.Summary Report') }}</a>
                        {!! Form::close() !!}
                    </li>
                @endif
                @if ($best_seller_active)
                    <li id="best-seller-report-menu">
                        <a href="{{ url('report/best_seller') }}">{{ trans('file.Best Seller') }}</a>
                    </li>
                @endif
                @if ($product_report_active)
                    <li id="product-report-menu">
                        {!! Form::open(['route' => 'report.product', 'method' => 'get', 'id' => 'product-report-form']) !!}
                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                        <input type="hidden" name="warehouse_id" value="0" />
                        <a id="report-link" href="">{{ trans('file.Product Report') }}</a>
                        {!! Form::close() !!}
                    </li>
                @endif
                @if ($daily_sale_active)
                    <li id="daily-sale-report-menu">
                        <a href="{{ url('report/daily_sale/' . date('Y') . '/' . date('m')) }}">
                            {{ trans('file.Daily Sale') }}
                        </a>
                    </li>
                @endif
                @if ($monthly_sale_active)
                    <li id="monthly-sale-report-menu">
                        <a href="{{ url('report/monthly_sale/' . date('Y')) }}">
                            {{ trans('file.Monthly Sale') }}
                        </a>
                    </li>
                @endif
                @if ($daily_purchase_active)
                    <li id="daily-purchase-report-menu">
                        <a href="{{ url('report/daily_purchase/' . date('Y') . '/' . date('m')) }}">
                            {{ trans('file.Daily Purchase') }}
                        </a>
                    </li>
                @endif
                @if ($monthly_purchase_active)
                    <li id="monthly-purchase-report-menu">
                        <a href="{{ url('report/monthly_purchase/' . date('Y')) }}">
                            {{ trans('file.Monthly Purchase') }}
                        </a>
                    </li>
                @endif
                @if ($sale_report_active)
                    <li id="sale-report-menu">
                        {!! Form::open(['route' => 'report.sale', 'method' => 'post', 'id' => 'sale-report-form']) !!}
                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                        <input type="hidden" name="warehouse_id" value="0" />
                        <a id="sale-report-link" href="">{{ trans('file.Sale Report') }}</a>
                        {!! Form::close() !!}
                    </li>
                @endif
                {{-- @if ($sale_report_chart_active)
                    <li id="sale-report-chart-menu">
                        {!! Form::open(['route' => 'report.saleChart', 'method' => 'post', 'id' => 'sale-report-chart-form']) !!}
                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                            <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                            <input type="hidden" name="warehouse_id" value="0" />
                            <input type="hidden" name="time_period" value="weekly" />
                            <a id="sale-report-chart-link" href="">{{ trans('file.Sale Report Chart') }}</a>
                            {!! Form::close() !!}
                    </li>
                    @endif --}}
                @if ($payment_report_active)
                    <li id="payment-report-menu">
                        {!! Form::open(['route' => 'report.paymentByDate', 'method' => 'post', 'id' => 'payment-report-form']) !!}
                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                        <a id="payment-report-link" href="">{{ trans('file.Payment Report') }}</a>
                        {!! Form::close() !!}
                    </li>
                @endif
                @if ($purchase_report_active)
                    <li id="purchase-report-menu">
                        {!! Form::open(['route' => 'report.purchase', 'method' => 'post', 'id' => 'purchase-report-form']) !!}
                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                        <input type="hidden" name="warehouse_id" value="0" />
                        <a id="purchase-report-link" href="">{{ trans('file.Purchase Report') }}</a>
                        {!! Form::close() !!}
                    </li>
                @endif
                @if ($customer_report_active)
                    <li id="customer-report-menu">
                        <a id="customer-report-link" href="">{{ trans('file.Customer Report') }}</a>
                    </li>
                @endif
                @if ($due_report_active)
                    <li id="due-report-menu">
                        {!! Form::open(['route' => 'report.customerDueByDate', 'method' => 'post', 'id' => 'customer-due-report-form']) !!}
                        <input type="hidden" name="start_date"
                            value="{{ date('Y-m-d', strtotime('-1 year')) }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                        <a id="due-report-link" href="">{{ trans('file.Customer Due Report') }}</a>
                        {!! Form::close() !!}
                    </li>
                @endif
                @if ($supplier_report_active)
                    <li id="supplier-report-menu">
                        <a id="supplier-report-link" href="">{{ trans('file.Supplier Report') }}</a>
                    </li>
                @endif
                {{-- @if ($supplier_due_report_active)
                    <li id="supplier-due-report-menu">
                        {!! Form::open(['route' => 'report.supplierDueByDate', 'method' => 'post', 'id' => 'supplier-due-report-form']) !!}
                        <input type="hidden" name="start_date"
                            value="{{ date('Y-m-d', strtotime('-1 year')) }}" />
                    <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />
                    <a id="supplier-due-report-link" href="">{{ trans('file.Supplier Due Report') }}</a>
                    {!! Form::close() !!}
                    </li>
                    @endif --}}
                @if ($warehouse_report_active)
                    <li id="warehouse-report-menu">
                        <a id="warehouse-report-link" href="">{{ trans('file.Warehouse Report') }}</a>
                    </li>
                @endif
                @if ($warehouse_stock_report_active)
                    <li id="warehouse-stock-report-menu">
                        <a href="{{ route('report.warehouseStock') }}">
                            {{ trans('file.Warehouse Stock Chart') }}
                        </a>
                    </li>
                @endif
                @if ($product_qty_alert_active)
                    <li id="qtyAlert-report-menu">
                        <a href="{{ route('report.qtyAlert') }}">{{ trans('file.Product Quantity Alert') }}</a>
                    </li>
                @endif
                {{-- @if ($dso_report_active)
                    <li id="daily-sale-objective-menu">
                        <a href="{{ route('report.dailySaleObjective') }}">
                            {{ trans('file.Daily Sale Objective Report') }}
                        </a>
                    </li>
                    @endif --}}
                @if ($user_report_active)
                    <li id="user-report-menu">
                        <a id="user-report-link" href="">{{ trans('file.User Report') }}</a>
                    </li>
                @endif
            </ul>
        </li>

        <li>
            <a href="#setting" aria-expanded="false" data-toggle="collapse">
                <i class="dripicons-gear"></i>
                <span>{{ trans('file.settings') }}</span>
            </a>
            <ul id="setting" class="collapse list-unstyled ">
                @if (Auth::user()->role_id <= 2)
                    <li id="role-menu">
                        <a href="{{ route('role.index') }}">{{ trans('file.Role Permission') }}</a>
                    </li>
                @endif
                @if ($discount_plan_active)
                    <li id="discount-plan-list-menu">
                        <a href="{{ route('discount-plans.index') }}">{{ trans('file.Discount Plan') }}</a>
                    </li>
                @endif
                @if ($discount_active)
                    <li id="discount-list-menu">
                        <a href="{{ route('discounts.index') }}">{{ trans('file.Discount') }}</a>
                    </li>
                @endif
                {{-- @if ($all_notification_active)
                        <li id="notification-list-menu">
                            <a href="{{ route('notifications.index') }}">{{ trans('file.All Notification') }}</a>
                        </li>
                        @endif --}}
                {{-- @if ($send_notification_active)
                        <li id="notification-menu">
                            <a href="" id="send-notification">{{ trans('file.Send Notification') }}</a>
                        </li>
                    @endif --}}
                @if ($customer_group_active)
                    <li id="customer-group-menu">
                        <a href="{{ route('customer_group.index') }}">{{ trans('file.Customer Group') }}</a>
                    </li>
                @endif
                @if ($tax_active)
                    <li id="tax-menu">
                        <a href="{{ route('tax.index') }}">{{ trans('file.Tax') }}</a>
                    </li>
                @endif
                <li id="user-menu">
                    <a href="{{ route('user.profile', ['id' => Auth::id()]) }}">
                        {{ trans('file.User Profile') }}
                    </a>
                </li>
                @if ($create_sms_active)
                    <li id="create-sms-menu">
                        <a href="{{ route('setting.createSms') }}">{{ trans('file.Create SMS') }}</a>
                    </li>
                @endif
                @if ($general_setting_active)
                    <li id="general-setting-menu">
                        <a href="{{ route('setting.general') }}">{{ trans('file.General Setting') }}</a>
                    </li>
                @endif
                {{-- @if ($mail_setting_permission_active)
                    <li id="mail-setting-menu">
                        <a href="{{ route('setting.mail') }}">{{ trans('file.Mail Setting') }}</a>
                    </li>
                    @endif --}}
                @if ($reward_point_setting_active)
                    <li id="reward-point-setting-menu">
                        <a href="{{ route('setting.rewardPoint') }}">{{ trans('file.Reward Point Setting') }}</a>
                    </li>
                @endif
                @if ($sms_setting_active)
                    <li id="sms-setting-menu">
                        <a href="{{ route('setting.sms') }}">{{ trans('file.SMS Setting') }}</a>
                    </li>
                @endif
                @if ($pos_setting_active)
                    <li id="pos-setting-menu">
                        <a href="{{ route('setting.pos') }}">
                            POS {{ trans('file.settings') }}
                        </a>
                    </li>
                @endif
                @if ($hrm_setting_active)
                    <li id="hrm-setting-menu">
                        <a href="{{ route('setting.hrm') }}">
                            {{ trans('file.HRM Setting') }}
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        @if (Auth::user()->role_id != 5)
            <li>
                <a href="{{ url('public/read_me') }}">
                    <i class="dripicons-information"></i>
                    <span>{{ trans('file.Documentation') }}</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
