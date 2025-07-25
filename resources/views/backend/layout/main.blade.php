@php
    $role_permission = [];
    foreach (Session::get('role_permissions') as $rp) {
        $role_name = $rp->name;

        $role_permission[str_replace('-', '_', $role_name) . '_active'] = $rp->role;
    }

    extract($role_permission);
@endphp

<!DOCTYPE html>
<html dir="@if (Config::get('app.locale') == 'ar' || $general_setting->is_rtl) {{ 'rtl' }} @endif">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @if (!config('database.connections.saleprosaas_landlord'))
        <link rel="icon" type="image/png" href="{{ url('logo', $general_setting->site_logo) }}" />
        <title>{{ $general_setting->site_title }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="all,follow">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="manifest" href="{{ url('manifest.json') }}">
        <!-- Bootstrap CSS-->
        <link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap.min.css'); ?>" type="text/css">
        <link rel="stylesheet" href="<?php echo asset('vendor/sweetalert/css/sweetalert2.min.css'); ?>" type="text/css">
        <link rel="preload" href="<?php echo asset('vendor/bootstrap-toggle/css/bootstrap-toggle.min.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('vendor/bootstrap-toggle/css/bootstrap-toggle.min.css'); ?>" rel="stylesheet">
        </noscript>
        <link rel="preload" href="<?php echo asset('vendor/bootstrap/css/bootstrap-datepicker.min.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <link rel="preload" href="<?php echo asset('vendor/jquery-timepicker/jquery.timepicker.min.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('vendor/jquery-timepicker/jquery.timepicker.min.css'); ?>" rel="stylesheet">
        </noscript>
        <link rel="preload" href="<?php echo asset('vendor/bootstrap/css/awesome-bootstrap-checkbox.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('vendor/bootstrap/css/awesome-bootstrap-checkbox.css'); ?>" rel="stylesheet">
        </noscript>
        <link rel="preload" href="<?php echo asset('vendor/bootstrap/css/bootstrap-select.min.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('vendor/bootstrap/css/bootstrap-select.min.css'); ?>" rel="stylesheet">
        </noscript>
        <!-- Font Awesome CSS-->
        <link rel="preload" href="<?php echo asset('vendor/font-awesome/css/font-awesome.min.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('vendor/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
        </noscript>
        <!-- Drip icon font-->
        <link rel="preload" href="<?php echo asset('vendor/dripicons/webfont.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('vendor/dripicons/webfont.css'); ?>" rel="stylesheet">
        </noscript>

        <!-- jQuery Circle-->
        <link rel="preload" href="<?php echo asset('css/grasp_mobile_progress_circle-1.0.0.min.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('css/grasp_mobile_progress_circle-1.0.0.min.css'); ?>" rel="stylesheet">
        </noscript>
        <!-- Custom Scrollbar-->
        <link rel="preload" href="<?php echo asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css'); ?>" rel="stylesheet">
        </noscript>

        @if (Route::current()->getName() != '/')
            <!-- date range stylesheet-->
            <link rel="preload" href="<?php echo asset('vendor/daterange/css/daterangepicker.min.css'); ?>" as="style"
                onload="this.onload=null;this.rel='stylesheet'">
            <noscript>
                <link href="<?php echo asset('vendor/daterange/css/daterangepicker.min.css'); ?>" rel="stylesheet">
            </noscript>
            <!-- table sorter stylesheet-->
            <link rel="preload" href="<?php echo asset('vendor/datatable/dataTables.bootstrap4.min.css'); ?>" as="style"
                onload="this.onload=null;this.rel='stylesheet'">
            <noscript>
                <link href="<?php echo asset('vendor/datatable/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet">
            </noscript>
            <link rel="preload" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css"
                as="style" onload="this.onload=null;this.rel='stylesheet'">
            <noscript>
                <link href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css"
                    rel="stylesheet">
            </noscript>
            <link rel="preload" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css"
                as="style" onload="this.onload=null;this.rel='stylesheet'">
            <noscript>
                <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css"
                    rel="stylesheet">
            </noscript>
        @endif

        <link rel="stylesheet" href="<?php echo asset('css/style.default.css'); ?>" id="theme-stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo asset('css/dropzone.css'); ?>">
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="<?php echo asset('css/custom-' . $general_setting->theme); ?>" type="text/css" id="custom-style">

        @if (Config::get('app.locale') == 'ar' || $general_setting->is_rtl)
            <!-- RTL css -->
            <link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap-rtl.min.css'); ?>" type="text/css">
            <link rel="stylesheet" href="<?php echo asset('css/custom-rtl.css'); ?>" type="text/css" id="custom-style">
        @endif
    @else
        <link rel="icon" type="image/png" href="{{ asset('logo', $general_setting->site_logo) }}" />
        <title>{{ $general_setting->site_title }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="all,follow">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="manifest" href="{{ url('manifest.json') }}">
        <!-- Bootstrap CSS-->
        <link rel="stylesheet" href="<?php echo asset('../../vendor/bootstrap/css/bootstrap.min.css'); ?>" type="text/css">
        <link rel="preload" href="<?php echo asset('../../vendor/bootstrap-toggle/css/bootstrap-toggle.min.css'); ?>" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('../../vendor/bootstrap-toggle/css/bootstrap-toggle.min.css'); ?>" rel="stylesheet">
        </noscript>
        <link rel="preload" href="<?php echo asset('../../vendor/bootstrap/css/bootstrap-datepicker.min.css'); ?>" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('../../vendor/bootstrap/css/bootstrap-datepicker.min.css'); ?>" rel="stylesheet">
        </noscript>
        <link rel="preload" href="<?php echo asset('../../vendor/jquery-timepicker/jquery.timepicker.min.css'); ?>" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('../../vendor/jquery-timepicker/jquery.timepicker.min.css'); ?>" rel="stylesheet">
        </noscript>
        <link rel="preload" href="<?php echo asset('../../vendor/bootstrap/css/awesome-bootstrap-checkbox.css'); ?>" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('../../vendor/bootstrap/css/awesome-bootstrap-checkbox.css'); ?>" rel="stylesheet">
        </noscript>
        <link rel="preload" href="<?php echo asset('../../vendor/bootstrap/css/bootstrap-select.min.css'); ?>" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('../../vendor/bootstrap/css/bootstrap-select.min.css'); ?>" rel="stylesheet">
        </noscript>
        <!-- Font Awesome CSS-->
        <link rel="preload" href="<?php echo asset('../../vendor/font-awesome/css/font-awesome.min.css'); ?>" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('../../vendor/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
        </noscript>
        <!-- Drip icon font-->
        <link rel="preload" href="<?php echo asset('../../vendor/dripicons/webfont.css'); ?>" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('../../vendor/dripicons/webfont.css'); ?>" rel="stylesheet">
        </noscript>

        <!-- jQuery Circle-->
        <link rel="preload" href="<?php echo asset('../../css/grasp_mobile_progress_circle-1.0.0.min.css'); ?>" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('../../css/grasp_mobile_progress_circle-1.0.0.min.css'); ?>" rel="stylesheet">
        </noscript>
        <!-- Custom Scrollbar-->
        <link rel="preload" href="<?php echo asset('../../vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css'); ?>" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="<?php echo asset('../../vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css'); ?>" rel="stylesheet">
        </noscript>

        @if (Route::current()->getName() != '/')
            <!-- date range stylesheet-->
            <link rel="preload" href="<?php echo asset('../../vendor/daterange/css/daterangepicker.min.css'); ?>" as="style"
                onload="this.onload=null;this.rel='stylesheet'">
            <noscript>
                <link href="<?php echo asset('../../vendor/daterange/css/daterangepicker.min.css'); ?>" rel="stylesheet">
            </noscript>
            <!-- table sorter stylesheet-->
            <link rel="preload" href="<?php echo asset('../../vendor/datatable/dataTables.bootstrap4.min.css'); ?>" as="style"
                onload="this.onload=null;this.rel='stylesheet'">
            <noscript>
                <link href="<?php echo asset('../../vendor/datatable/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet">
            </noscript>
            <link rel="preload" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css"
                as="style" onload="this.onload=null;this.rel='stylesheet'">
            <noscript>
                <link href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css"
                    rel="stylesheet">
            </noscript>
            <link rel="preload" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css"
                as="style" onload="this.onload=null;this.rel='stylesheet'">
            <noscript>
                <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css"
                    rel="stylesheet">
            </noscript>
        @endif

        <link rel="stylesheet" href="<?php echo asset('../../css/style.default.css'); ?>" id="theme-stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo asset('../../css/dropzone.css'); ?>">
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="<?php echo asset('../../css/custom-' . $general_setting->theme); ?>" type="text/css" id="custom-style">

        @if (Config::get('app.locale') == 'ar' || $general_setting->is_rtl)
            <!-- RTL css -->
            <link rel="stylesheet" href="<?php echo asset('../../vendor/bootstrap/css/bootstrap-rtl.min.css'); ?>" type="text/css">
            <link rel="stylesheet" href="<?php echo asset('../../css/custom-rtl.css'); ?>" type="text/css" id="custom-style">
        @endif
    @endif
    <!-- Google fonts - Roboto -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Nunito:400,500,700" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,500,700" rel="stylesheet">
    </noscript>

    <style>
        .dark-mode .dropdown-item:focus,
        .dark-mode .dropdown-item:hover {
            color: #fff;
        }
    </style>
</head>

<body @if ($theme == 'dark') class="dark-mode mt-3" @else class="" @endif onload="myFunction()">
    <?php $role = DB::connection(env('TENANT_DB_CONNECTION'))
        ->table('roles')
        ->find(Auth::user()->role_id); ?>
    <div id="loader"></div>
    <nav class="side-navbar">
        <span class="brand-big">
            @if ($general_setting->site_logo)
                <a href="{{ url('/') }}"><img src="<?php echo asset('uploadedimages/' . $general_setting->site_logo); ?>" height="50"></a>
            @else
                <a href="{{ url('/') }}">
                    <h1 class="d-inline">{{ $general_setting->site_title }}</h1>
                </a>
            @endif
        </span>

        <ul id="side-main-menu" class="side-menu list-unstyled">
            <li>
                <a href="{{ url('/') }}">
                    <i class="dripicons-meter"></i><span>{{ __('file.dashboard') }}</span>
                </a>
            </li>

            <li>
                <a href="#setting" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-gear"></i><span>{{ trans('file.settings') }}</span>
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
                            <a href="{{ route('discounts.index') }}">{{ trans('file.Discount Group') }}</a>
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
                        <a
                            href="{{ route('user.profile', ['id' => Auth::id()]) }}">{{ trans('file.User Profile') }}</a>
                    </li>
                    {{-- @if ($create_sms_active)
                        <li id="create-sms-menu">
                            <a href="{{ route('setting.createSms') }}">{{ trans('file.Create SMS') }}</a>
                        </li>
                    @endif --}}
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
                    {{-- @if ($reward_point_setting_active)
                        <li id="reward-point-setting-menu">
                            <a
                                href="{{ route('setting.rewardPoint') }}">{{ trans('file.Reward Point Setting') }}</a>
                        </li>
                    @endif --}}
                    {{-- @if ($sms_setting_active)
                        <li id="sms-setting-menu">
                            <a href="{{ route('setting.sms') }}">{{ trans('file.SMS Setting') }}</a>
                        </li>
                    @endif --}}
                    @if ($pos_setting_active)
                        <li id="pos-setting-menu">
                            <a href="{{ route('setting.pos') }}">POS
                                {{ trans('file.settings') }}</a>
                        </li>
                    @endif
                    {{-- @if ($hrm_setting_active)
                        <li id="hrm-setting-menu">
                            <a href="{{ route('setting.hrm') }}">
                                {{ trans('file.HRM Setting') }}</a>
                        </li>
                    @endif --}}
                </ul>
            </li>

            <li>
                <a href="#people" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-user"></i><span>{{ trans('file.People') }}</span>
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
                <a href="#product" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-list"></i><span>{{ __('file.product') }}</span>
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
                        <li id="product-list-menu"><a
                                href="{{ route('products.index') }}">{{ __('file.product_list') }}</a>
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
                        <i class="dripicons-card"></i><span>{{ trans('file.Purchase') }}</span>
                    </a>
                    <ul id="purchase" class="collapse list-unstyled ">
                        <li id="purchase-list-menu">
                            <a href="{{ route('purchases.index') }}">{{ trans('file.Purchase List') }}</a>
                        </li>
                        @if ($purchases_add_active)
                            <li id="purchase-create-menu">
                                <a href="{{ route('purchases.create') }}">{{ trans('file.Add Purchase') }}</a>
                            </li>
                            {{-- <li id="purchase-import-menu">
                                <a
                                    href="{{ url('purchases/purchase_by_csv') }}">{{ trans('file.Import Purchase By CSV') }}</a>
                            </li> --}}
                        @endif
                        @if ($purchase_return_index_active)
                            <li id="purchase-return-menu">
                                <a
                                    href="{{ route('return-purchase.index') }}">{{ trans('file.Purchase Return') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <li>
                <a href="#sale" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-cart"></i><span>{{ trans('file.Sale') }}</span>
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
                            {{-- <li id="sale-import-menu">
                                <a href="{{ url('sales/sale_by_csv') }}">{{ trans('file.Import Sale By CSV') }}</a>
                            </li> --}}
                        @endif
                    @endif
                    {{-- @if ($gift_card_active)
                        <li id="gift-card-menu">
                            <a href="{{ route('gift_cards.index') }}">{{ trans('file.Gift Card List') }}</a>
                        </li>
                    @endif --}}
                    {{-- @if ($coupon_active)
                        <li id="coupon-menu">
                            <a href="{{ route('coupons.index') }}">{{ trans('file.Coupon List') }}</a>
                        </li>
                    @endif --}}
                    <li id="delivery-menu">
                        <a href="{{ route('delivery.index') }}">{{ trans('file.Delivery List') }}</a>
                    </li>
                    @if ($returns_index_active)
                        <li id="sale-return-menu">
                            <a href="{{ route('return-sale.index') }}">{{ trans('file.Sale Return') }}</a>
                        </li>
                    @endif
                </ul>
            </li>

            {{-- @if ($expenses_index_active)
                <li>
                    <a href="#expense" aria-expanded="false" data-toggle="collapse">
                        <i class="dripicons-wallet"></i><span>{{ trans('file.Expense') }}</span>
                    </a>
                    <ul id="expense" class="collapse list-unstyled ">
                        <li id="exp-cat-menu">
                            <a
                                href="{{ route('expense_categories.index') }}">{{ trans('file.Expense Category') }}</a>
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
            @endif --}}

            {{-- @if ($quotes_index_active)
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
            @endif --}}

            {{-- @if ($transfers_index_active)
                <li>
                    <a href="#transfer" aria-expanded="false" data-toggle="collapse">
                        <i class="dripicons-export"></i><span>{{ trans('file.Transfer') }}</span>
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
                                <a
                                    href="{{ url('transfers/transfer_by_csv') }}">{{ trans('file.Import Transfer By CSV') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif --}}

            @if ($account_index_active || $balance_sheet_active || $account_statement_active)
                <li class="">
                    <a href="#account" aria-expanded="false" data-toggle="collapse">
                        <i class="dripicons-briefcase"></i><span>{{ trans('file.Accounting') }}</span>
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
                        @if ($account_close_statement_active)
                            <li id="tutup-buku-menu">
                                <a href="{{ route('tutup_buku.index') }}">
                                    {{ trans('file.Close the book') }}
                                </a>
                            </li>
                        @endif
                        {{-- @if ($money_transfer_active)
                            <li id="money-transfer-menu">
                                <a href="{{ route('money-transfers.index') }}">{{ trans('file.Money Transfer') }}</a>
                            </li>
                        @endif --}}
                        {{-- @if ($balance_sheet_active)
                            <li id="balance-sheet-menu">
                                <a href="{{ route('accounts.balancesheet') }}">{{ trans('file.Balance Sheet') }}</a>
                            </li>
                        @endif --}}
                        {{-- @if ($account_statement_active)
                            <li id="account-statement-menu">
                                <a id="account-statement" href="">{{ trans('file.Account Statement') }}</a>
                            </li>
                        @endif --}}
                    </ul>
                </li>
            @endif

            {{-- <li class="">
                <a href="#hrm" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-user-group"></i><span>HRM</span>
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
            </li> --}}

            <li>
                <a href="#report" aria-expanded="false" data-toggle="collapse">
                    <i class="dripicons-document-remove"></i><span>{{ trans('file.Reports') }}</span>
                </a>
                <ul id="report" class="collapse list-unstyled ">
                    @if ($account_report_active)
                        <li id="laporan-keuangan-menu">
                            <a href="{{ route('laporan_keuangan') }}">
                                {{ trans('file.Financial Statements') }}
                            </a>
                        </li>
                    @endif
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
                            <a
                                href="{{ url('report/daily_sale/' . date('Y') . '/' . date('m')) }}">{{ trans('file.Daily Sale') }}</a>
                        </li>
                    @endif
                    @if ($monthly_sale_active)
                        <li id="monthly-sale-report-menu">
                            <a
                                href="{{ url('report/monthly_sale/' . date('Y')) }}">{{ trans('file.Monthly Sale') }}</a>
                        </li>
                    @endif
                    @if ($daily_purchase_active)
                        <li id="daily-purchase-report-menu">
                            <a
                                href="{{ url('report/daily_purchase/' . date('Y') . '/' . date('m')) }}">{{ trans('file.Daily Purchase') }}</a>
                        </li>
                    @endif
                    @if ($monthly_purchase_active)
                        <li id="monthly-purchase-report-menu">
                            <a
                                href="{{ url('report/monthly_purchase/' . date('Y')) }}">{{ trans('file.Monthly Purchase') }}</a>
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
                            <a
                                href="{{ route('report.warehouseStock') }}">{{ trans('file.Warehouse Stock Chart') }}</a>
                        </li>
                    @endif
                    @if ($product_qty_alert_active)
                        <li id="qtyAlert-report-menu">
                            <a href="{{ route('report.qtyAlert') }}">{{ trans('file.Product Quantity Alert') }}</a>
                        </li>
                    @endif
                    {{-- @if ($dso_report_active)
                        <li id="daily-sale-objective-menu">
                            <a
                                href="{{ route('report.dailySaleObjective') }}">{{ trans('file.Daily Sale Objective Report') }}</a>
                        </li>
                    @endif --}}
                    @if ($user_report_active)
                        <li id="user-report-menu">
                            <a id="user-report-link" href="">{{ trans('file.User Report') }}</a>
                        </li>
                    @endif
                </ul>
            </li>
            {{-- @if (Auth::user()->role_id != 5)
                <li>
                    <a href="{{ url('public/read_me') }}">
                        <i class="dripicons-information"></i><span>{{ trans('file.Documentation') }}</span>
                    </a>
                </li>
            @endif --}}
        </ul>
    </nav>
    <div class="page">
        <!-- navbar-->
        <header class="container-fluid">
            <nav class="navbar">
                <a id="toggle-btn" href="#" class="menu-btn"><i class="fa fa-bars"> </i></a>
                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                    @if ($sales_add_active)
                        <li class="nav-item"><a class="dropdown-item btn-pos btn-sm"
                                href="{{ route('sale.pos') }}"><i class="dripicons-shopping-bag"></i><span>
                                    POS</span></a></li>
                    @endif
                    <li class="nav-item"><a id="switch-theme" data-toggle="tooltip"
                            title="{{ trans('file.Switch Theme') }}"><i class="dripicons-brightness-max"></i></a>
                    </li>
                    <li class="nav-item"><a id="btnFullscreen" data-toggle="tooltip"
                            title="{{ trans('file.Full Screen') }}"><i class="dripicons-expand"></i></a></li>
                    @if (\Auth::user()->role_id <= 2)
                        <li class="nav-item"><a href="{{ route('cashRegister.index') }}" data-toggle="tooltip"
                                title="{{ trans('file.Cash Register List') }}"><i class="dripicons-archive"></i></a>
                        </li>
                    @endif
                    @if ($product_qty_alert_active && $alert_product + $dso_alert_product_no + count(\Auth::user()->unreadNotifications) > 0)
                        <li class="nav-item" id="notification-icon">
                            <a rel="nofollow" data-toggle="tooltip" title="{{ __('Notifications') }}"
                                class="nav-link dropdown-item"><i class="dripicons-bell"></i><span
                                    class="badge badge-danger notification-number">{{ $alert_product + $dso_alert_product_no + count(\Auth::user()->unreadNotifications) }}</span>
                            </a>
                            <ul class="right-sidebar">
                                <li class="notifications">
                                    <a href="{{ route('report.qtyAlert') }}" class="btn btn-link">
                                        {{ $alert_product }} product exceeds alert quantity</a>
                                </li>
                                @if ($dso_alert_product_no)
                                    <li class="notifications">
                                        <a href="{{ route('report.dailySaleObjective') }}" class="btn btn-link">
                                            {{ $dso_alert_product_no }} product could not fulfill daily sale
                                            objective</a>
                                    </li>
                                @endif
                                @foreach (\Auth::user()->unreadNotifications as $key => $notification)
                                    <li class="notifications">
                                        @if ($notification->data['document_name'])
                                            <a target="_blank"
                                                href="{{ url('public/documents/notification', $notification->data['document_name']) }}"
                                                class="btn btn-link">{{ $notification->data['message'] }}</a>
                                        @else
                                            <a href="#"
                                                class="btn btn-link">{{ $notification->data['message'] }}</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                    @if (count(\Auth::user()->unreadNotifications) > 0)
                        <li class="nav-item" id="notification-icon">
                            <a rel="nofollow" data-toggle="tooltip" title="{{ __('Notifications') }}"
                                class="nav-link dropdown-item"><i class="dripicons-bell"></i><span
                                    class="badge badge-danger notification-number">{{ count(\Auth::user()->unreadNotifications) }}</span>
                            </a>
                            <ul class="right-sidebar">
                                @foreach (\Auth::user()->unreadNotifications as $key => $notification)
                                    <li class="notifications">
                                        @if ($notification->data['document_name'])
                                            <a target="_blank"
                                                href="{{ url('public/documents/notification', $notification->data['document_name']) }}"
                                                class="btn btn-link">{{ $notification->data['message'] }}</a>
                                        @else
                                            <a href="#"
                                                class="btn btn-link">{{ $notification->data['message'] }}</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a rel="nofollow" title="{{ trans('file.language') }}" data-toggle="tooltip"
                            class="nav-link dropdown-item"><i class="dripicons-web"></i></a>
                        <ul class="right-sidebar">
                            <li>
                                <a href="{{ url('language_switch/id') }}" class="btn btn-link"> Indonesia</a>
                            </li>
                            <li>
                                <a href="{{ url('language_switch/en') }}" class="btn btn-link"> English</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a rel="nofollow" data-toggle="tooltip" class="nav-link dropdown-item"><i
                                class="dripicons-user"></i> <span>{{ ucfirst(Auth::user()->name) }}</span> <i
                                class="fa fa-angle-down"></i>
                        </a>
                        <ul class="right-sidebar">
                            <li>
                                <a href="{{ route('user.profile', ['id' => Auth::id()]) }}"><i
                                        class="dripicons-user"></i> {{ trans('file.profile') }}</a>
                            </li>
                            @if ($general_setting_active)
                                <li>
                                    <a href="{{ route('setting.general') }}"><i class="dripicons-gear"></i>
                                        {{ trans('file.settings') }}</a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ url('my-transactions/' . date('Y') . '/' . date('m')) }}"><i
                                        class="dripicons-swap"></i> {{ trans('file.My Transaction') }}</a>
                            </li>
                            @if (Auth::user()->role_id != 5)
                                <li>
                                    <a href="{{ url('holidays/my-holiday/' . date('Y') . '/' . date('m')) }}"><i
                                            class="dripicons-vibrate"></i> {{ trans('file.My Holiday') }}</a>
                                </li>
                            @endif
                            @if ($empty_database_active)
                                <li>
                                    <a onclick="return confirm('Are you sure want to delete? If you do this all of your data will be lost.')"
                                        href="{{ route('setting.emptyDatabase') }}"><i class="dripicons-stack"></i>
                                        {{ trans('file.Empty Database') }}</a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();"><i
                                        class="dripicons-power"></i>
                                    {{ trans('file.logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
        <!-- notification modal -->
        <div id="notification-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Send Notification') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'notifications.store', 'method' => 'post', 'files' => true]) !!}
                        <div class="row">
                            <?php
                            $lims_user_list = DB::connection(env('TENANT_DB_CONNECTION'))
                                ->table('users')
                                ->where([['is_active', true], ['id', '!=', \Auth::user()->id]])
                                ->get();
                            ?>
                            <div class="col-md-6 form-group">
                                <input type="hidden" name="sender_id" value="{{ \Auth::id() }}">
                                <label>{{ trans('file.User') }} *</label>
                                <select name="receiver_id" class="selectpicker form-control" required
                                    data-live-search="true" data-live-search-style="begins" title="Select user...">
                                    @foreach ($lims_user_list as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name . ' (' . $user->email . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Attach Document') }}</label>
                                <input type="file" name="document" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>{{ trans('file.Message') }} *</label>
                                <textarea rows="5" name="message" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end notification modal -->

        <!-- expense modal -->
        <div id="expense-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Expense') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'expenses.store', 'method' => 'post']) !!}
                        <?php
                        $lims_expense_category_list = DB::connection(env('TENANT_DB_CONNECTION'))->table('expense_categories')->where('is_active', true)->get();
                        if (Auth::user()->role_id > 2) {
                            $lims_warehouse_list = DB::connection(env('TENANT_DB_CONNECTION'))
                                ->table('warehouses')
                                ->where([['is_active', true], ['id', Auth::user()->warehouse_id]])
                                ->get();
                        } else {
                            $lims_warehouse_list = DB::connection(env('TENANT_DB_CONNECTION'))->table('warehouses')->where('is_active', true)->get();
                        }
                        $lims_account_list = \App\Account::where('is_active', true)->get();
                        ?>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Date') }}</label>
                                <input type="text" name="created_at" class="form-control date"
                                    placeholder="Choose date" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Expense Category') }} *</label>
                                <select name="expense_category_id" class="selectpicker form-control" required
                                    data-live-search="true" data-live-search-style="begins"
                                    title="Select Expense Category...">
                                    @foreach ($lims_expense_category_list as $expense_category)
                                        <option value="{{ $expense_category->id }}">
                                            {{ $expense_category->name . ' (' . $expense_category->code . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Warehouse') }} *</label>
                                <select name="warehouse_id" class="selectpicker form-control" required
                                    data-live-search="true" data-live-search-style="begins"
                                    title="Select Warehouse...">
                                    @foreach ($lims_warehouse_list as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('file.Amount') }} *</label>
                                <input type="number" name="amount" step="any" required class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label> {{ trans('file.Account') }}</label>
                                <select class="form-control selectpicker" name="account_id">
                                    @foreach ($lims_account_list as $account)
                                        @if ($account->is_default)
                                            <option selected value="{{ $account->id }}">{{ $account->name }}
                                                [{{ $account->account_no }}]</option>
                                        @else
                                            <option value="{{ $account->id }}">{{ $account->name }}
                                                [{{ $account->account_no }}]</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('file.Note') }}</label>
                            <textarea name="note" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end expense modal -->

        <!-- account modal -->
        <div id="account-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Add Account') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'accounts.store', 'method' => 'post']) !!}
                        <div class="form-group">
                            <label>{{ trans('file.Account No') }} *</label>
                            <input type="text" name="account_no" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ trans('file.name') }} *</label>
                            <input type="text" name="name" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ trans('file.Initial Balance') }}</label>
                            <input type="number" name="initial_balance" step="any" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{ trans('file.Note') }}</label>
                            <textarea name="note" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end account modal -->

        <!-- account statement modal -->
        <div id="account-statement-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Account Statement') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'accounts.statement', 'method' => 'post']) !!}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label> {{ trans('file.Account') }}</label>
                                <select class="form-control selectpicker" name="account_id">
                                    @foreach ($lims_account_list as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}
                                            [{{ $account->account_no }}]</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label> {{ trans('file.Type') }}</label>
                                <select class="form-control selectpicker" name="type">
                                    <option value="0">{{ trans('file.All') }}</option>
                                    <option value="1">{{ trans('file.Debit') }}</option>
                                    <option value="2">{{ trans('file.Credit') }}</option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>{{ trans('file.Choose Your Date') }}</label>
                                <div class="input-group">
                                    <input type="text" class="account-statement-daterangepicker-field form-control"
                                        required />
                                    <input type="hidden" name="start_date" />
                                    <input type="hidden" name="end_date" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end account statement modal -->

        <!-- warehouse modal -->
        <div id="warehouse-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Warehouse Report') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'report.warehouse', 'method' => 'post']) !!}

                        <div class="form-group">
                            <label>{{ trans('file.Warehouse') }} *</label>
                            <select name="warehouse_id" class="selectpicker form-control" required
                                data-live-search="true" id="warehouse-id" data-live-search-style="begins"
                                title="Select warehouse...">
                                @foreach ($lims_warehouse_list as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end warehouse modal -->

        <!-- user modal -->
        <div id="user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.User Report') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'report.user', 'method' => 'post']) !!}
                        <?php
                        $lims_user_list = DB::connection(env('TENANT_DB_CONNECTION'))->table('users')->where('is_active', true)->get();
                        ?>
                        <div class="form-group">
                            <label>{{ trans('file.User') }} *</label>
                            <select name="user_id" class="selectpicker form-control" required data-live-search="true"
                                id="user-id" data-live-search-style="begins" title="Select user...">
                                @foreach ($lims_user_list as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name . ' (' . $user->phone . ')' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end user modal -->

        <!-- customer modal -->
        <div id="customer-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Customer Report') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'report.customer', 'method' => 'post']) !!}
                        <?php
                        $lims_customer_list = DB::connection(env('TENANT_DB_CONNECTION'))->table('customers')->where('is_active', true)->get();
                        ?>
                        <div class="form-group">
                            <label>{{ trans('file.customer') }} *</label>
                            <select name="customer_id" class="selectpicker form-control" required
                                data-live-search="true" id="customer-id" data-live-search-style="begins"
                                title="Select customer...">
                                @foreach ($lims_customer_list as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name . ' (' . $customer->phone_number . ')' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end customer modal -->

        <!-- supplier modal -->
        <div id="supplier-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Supplier Report') }}</h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <p class="italic">
                            <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                        </p>
                        {!! Form::open(['route' => 'report.supplier', 'method' => 'post']) !!}
                        <?php
                        $lims_supplier_list = DB::connection(env('TENANT_DB_CONNECTION'))->table('suppliers')->where('is_active', true)->get();
                        ?>
                        <div class="form-group">
                            <label>{{ trans('file.Supplier') }} *</label>
                            <select name="supplier_id" class="selectpicker form-control" required
                                data-live-search="true" id="supplier-id" data-live-search-style="begins"
                                title="Select Supplier...">
                                @foreach ($lims_supplier_list as $supplier)
                                    <option value="{{ $supplier->id }}">
                                        {{ $supplier->name . ' (' . $supplier->phone_number . ')' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="start_date" value="{{ date('Y-m') . '-' . '01' }}" />
                        <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}" />

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('file.submit') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- end supplier modal -->

        <div style="display:none" id="content" class="animate-bottom">
            @yield('content')
        </div>

        <footer class="main-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <p>&copy; {{ $general_setting->site_title }} | {{ trans('file.Developed') }}
                            {{ trans('file.By') }} <span
                                class="external">{{ $general_setting->developed_by }}</span> | V 3.8</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    @if (!config('database.connections.saleprosaas_landlord'))
        <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery-ui.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/jquery/bootstrap-datepicker.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.timepicker.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/popper.js/umd/popper.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/bootstrap/js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/bootstrap-toggle/js/bootstrap-toggle.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/bootstrap/js/bootstrap-select.min.js'); ?>"></script>

        <script type="text/javascript" src="<?php echo asset('js/grasp_mobile_progress_circle-1.0.0.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/jquery.cookie/jquery.cookie.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/chart.js/Chart.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('js/charts-custom.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/jquery-validation/jquery.validate.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js'); ?>"></script>
        @if (Config::get('app.locale') == 'ar' || $general_setting->is_rtl)
            <script type="text/javascript" src="<?php echo asset('js/front_rtl.js'); ?>"></script>
        @else
            <script type="text/javascript" src="<?php echo asset('js/front.js'); ?>"></script>
        @endif

        @if (Route::current()->getName() != '/')
            <script type="text/javascript" src="<?php echo asset('vendor/daterange/js/moment.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/daterange/js/knockout-3.4.2.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/daterange/js/daterangepicker.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/tinymce/js/tinymce/tinymce.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('js/dropzone.js'); ?>"></script>

            <!-- table sorter js-->
            @if (Config::get('app.locale') == 'ar')
                <script type="text/javascript" src="<?php echo asset('vendor/datatable/pdfmake_arabic.min.js'); ?>"></script>
                <script type="text/javascript" src="<?php echo asset('vendor/datatable/vfs_fonts_arabic.js'); ?>"></script>
            @else
                <script type="text/javascript" src="<?php echo asset('vendor/datatable/pdfmake.min.js'); ?>"></script>
                <script type="text/javascript" src="<?php echo asset('vendor/datatable/vfs_fonts.js'); ?>"></script>
            @endif
            <script type="text/javascript" src="<?php echo asset('vendor/datatable/jquery.dataTables.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/datatable/dataTables.bootstrap4.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/datatable/dataTables.buttons.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/datatable/jszip.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/datatable/buttons.bootstrap4.min.js'); ?>">
                ">
            </script>
            <script type="text/javascript" src="<?php echo asset('vendor/datatable/buttons.colVis.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/datatable/buttons.html5.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/datatable/buttons.printnew.js'); ?>"></script>

            <script type="text/javascript" src="<?php echo asset('vendor/datatable/sum().js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('vendor/datatable/dataTables.checkboxes.min.js'); ?>"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js">
            </script>
            <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js">
            </script>
            <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js">
            </script>
        @endif
    @else
        <script type="text/javascript" src="<?php echo asset('../../vendor/jquery/jquery.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/jquery/jquery-ui.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/jquery/bootstrap-datepicker.min.js'); ?>"></script>

        <script type="text/javascript" src="<?php echo asset('../../vendor/jquery/jquery.timepicker.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/popper.js/umd/popper.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/bootstrap/js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/bootstrap-toggle/js/bootstrap-toggle.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/bootstrap/js/bootstrap-select.min.js'); ?>"></script>

        <script type="text/javascript" src="<?php echo asset('../../js/grasp_mobile_progress_circle-1.0.0.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/jquery.cookie/jquery.cookie.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/chart.js/Chart.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../js/charts-custom.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/jquery-validation/jquery.validate.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo asset('../../vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js'); ?>"></script>
        @if (Config::get('app.locale') == 'ar' || $general_setting->is_rtl)
            <script type="text/javascript" src="<?php echo asset('../../js/front_rtl.js'); ?>"></script>
        @else
            <script type="text/javascript" src="<?php echo asset('../../js/front.js'); ?>"></script>
        @endif

        @if (Route::current()->getName() != '/')
            <script type="text/javascript" src="<?php echo asset('../../vendor/daterange/js/moment.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/daterange/js/knockout-3.4.2.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/daterange/js/daterangepicker.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/tinymce/js/tinymce/tinymce.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../js/dropzone.js'); ?>"></script>

            <!-- table sorter js-->
            @if (Config::get('app.locale') == 'ar')
                <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/pdfmake_arabic.min.js'); ?>"></script>
                <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/vfs_fonts_arabic.js'); ?>"></script>
            @else
                <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/pdfmake.min.js'); ?>"></script>
                <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/vfs_fonts.js'); ?>"></script>
            @endif
            <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/jquery.dataTables.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/dataTables.bootstrap4.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/dataTables.buttons.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/buttons.bootstrap4.min.js'); ?>">
                ">
            </script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/buttons.colVis.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/buttons.html5.min.js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/buttons.printnew.js'); ?>"></script>

            <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/sum().js'); ?>"></script>
            <script type="text/javascript" src="<?php echo asset('../../vendor/datatable/dataTables.checkboxes.min.js'); ?>"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js">
            </script>
            <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js">
            </script>
            <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js">
            </script>
        @endif
    @endif
    <script src="https://cdn.jsdelivr.net/gh/plentz/jquery-maskmoney@master/dist/jquery.maskMoney.min.js"></script>
    <script type="text/javascript" src="<?php echo asset('../../vendor/sweetalert/js/sweetalert2.all.min.js'); ?>"></script>
    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/salepro/service-worker.js').then(function(registration) {
                    // Registration was successful
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    // registration failed :(
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
    <script type="text/javascript">
        $("input").attr("autocomplete", "off");

        var theme = <?php echo json_encode($theme); ?>;
        if (theme == 'dark') {
            $('body').addClass('dark-mode');
            $('#switch-theme i').addClass('dripicons-brightness-low');
        } else {
            $('body').removeClass('dark-mode');
            $('#switch-theme i').addClass('dripicons-brightness-max');
        }
        $('#switch-theme').click(function() {
            if (theme == 'light') {
                theme = 'dark';
                var url = <?php echo json_encode(route('switchTheme', 'dark')); ?>;
                $('body').addClass('dark-mode');
                $('#switch-theme i').addClass('dripicons-brightness-low');
            } else {
                theme = 'light';
                var url = <?php echo json_encode(route('switchTheme', 'light')); ?>;
                $('body').removeClass('dark-mode');
                $('#switch-theme i').addClass('dripicons-brightness-max');
            }

            $.get(url, function(data) {
                console.log('theme changed to ' + theme);
            });
        });

        var alert_product = <?php echo json_encode($alert_product); ?>;

        if ($(window).outerWidth() > 1199) {
            $('nav.side-navbar').removeClass('shrink');
        }

        function myFunction() {
            setTimeout(showPage, 100);
        }

        function showPage() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("content").style.display = "block";
        }

        $("div.alert").delay(4000).slideUp(800);

        function confirmDelete() {
            if (confirm("Are you sure want to delete?")) {
                return true;
            }
            return false;
        }

        $("li#notification-icon").on("click", function(argument) {
            $.get('notifications/mark-as-read', function(data) {
                $("span.notification-number").text(alert_product);
            });
        });

        $("a#add-expense").click(function(e) {
            e.preventDefault();
            $('#expense-modal').modal();
        });

        $("a#send-notification").click(function(e) {
            e.preventDefault();
            $('#notification-modal').modal();
        });

        $("a#add-account").click(function(e) {
            e.preventDefault();
            $('#account-modal').modal();
        });

        $("a#account-statement").click(function(e) {
            e.preventDefault();
            $('#account-statement-modal').modal();
        });

        $("a#profitLoss-link").click(function(e) {
            e.preventDefault();
            $("#profitLoss-report-form").submit();
        });

        $("a#report-link").click(function(e) {
            e.preventDefault();
            $("#product-report-form").submit();
        });

        $("a#purchase-report-link").click(function(e) {
            e.preventDefault();
            $("#purchase-report-form").submit();
        });

        $("a#sale-report-link").click(function(e) {
            e.preventDefault();
            $("#sale-report-form").submit();
        });
        $("a#sale-report-chart-link").click(function(e) {
            e.preventDefault();
            $("#sale-report-chart-form").submit();
        });

        $("a#payment-report-link").click(function(e) {
            e.preventDefault();
            $("#payment-report-form").submit();
        });

        $("a#warehouse-report-link").click(function(e) {
            e.preventDefault();
            $('#warehouse-modal').modal();
        });

        $("a#user-report-link").click(function(e) {
            e.preventDefault();
            $('#user-modal').modal();
        });

        $("a#customer-report-link").click(function(e) {
            e.preventDefault();
            $('#customer-modal').modal();
        });

        $("a#supplier-report-link").click(function(e) {
            e.preventDefault();
            $('#supplier-modal').modal();
        });

        $("a#due-report-link").click(function(e) {
            e.preventDefault();
            $("#customer-due-report-form").submit();
        });

        $("a#supplier-due-report-link").click(function(e) {
            e.preventDefault();
            $("#supplier-due-report-form").submit();
        });

        $(".account-statement-daterangepicker-field").daterangepicker({
            callback: function(startDate, endDate, period) {
                var start_date = startDate.format('YYYY-MM-DD');
                var end_date = endDate.format('YYYY-MM-DD');
                var title = start_date + ' To ' + end_date;
                $(this).val(title);
                $('#account-statement-modal input[name="start_date"]').val(start_date);
                $('#account-statement-modal input[name="end_date"]').val(end_date);
            }
        });

        $('.date').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true
        });

        $('.selectpicker').selectpicker({
            style: 'btn-link',
        });

        function toDecimal(angka) {
            let decimal = angka.toLocaleString('en-US', {
                minimumFractionDigits: 2
            });
            return decimal;
        }

        function toNumber(angka) {
            let number = parseFloat(angka.replace(/,/g, ''));
            return number;
        }

        function preLoad() {
            $('.form-control.mask').maskMoney({
                precision: 0
            })
            $('.form-control.decimal').maskMoney()

            $('input.form-control').on('focus', function() {
                let input = this;
                setTimeout(function() {
                    input.select();
                }, 100);
            });
        }

        $(document).ready(function() {
            preLoad()
        })
    </script>
</body>

</html>
