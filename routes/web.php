<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginAdminController;
use App\Http\Controllers\BillerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DiscountPlanController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ReturnPurchaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockCountController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TutupBukuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;

Route::get('clear', function () {
	Artisan::call('optimize:clear');
	dd('success');
});

Route::get('/', function () {
	die('x');
});
Route::get('admin/login', [LoginAdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginAdminController::class, 'doLogin'])->name('admin.login');

Route::group(['middleware' => ['auth:admin']], function () {
	Route::get('admin/home', [AdminController::class, 'index'])->name('admin.home');
	Route::get('admin/logout', [LoginAdminController::class, 'logout'])->name('admin.logout');
	Route::get('admin/tenant', [TenantController::class, 'index'])->name('admin.tenant');
	Route::post('admin/tenant/getdata', [TenantController::class, 'getData'])->name('admin.tenant.getdata');
	Route::get('admin/tenant/create', [TenantController::class, 'create'])->name('admin.tenant.create');
	Route::get('admin/tenant/edit/{id}', [TenantController::class, 'edit'])->name('admin.tenant.edit');
	Route::post('admin/tenant/store', [TenantController::class, 'store'])->name('admin.tenant.store');
	Route::post('admin/tenant/update/{id}', [TenantController::class, 'update'])->name('admin.tenant.update');
	Route::get('admin/tenant/delete', [TenantController::class, 'delete'])->name('admin.tenant.delete');
});

Auth::routes();

Route::group(['middleware' => ['auth:web']], function () {
	Route::get('/dashboard', [HomeController::class, 'dashboard']);
});

Route::group(['middleware' => ['common', 'auth:web', 'active']], function () {
	Route::get('/', [HomeController::class, 'index']);
	Route::get('switch-theme/{theme}', [HomeController::class, 'switchTheme'])->name('switchTheme');
	Route::get('/dashboard-filter/{start_date}/{end_date}', [HomeController::class, 'dashboardFilter']);
	Route::get('check-batch-availability/{product_id}/{batch_no}/{warehouse_id}', [ProductController::class, 'checkBatchAvailability']);

	Route::get('language_switch/{locale}', [LanguageController::class, 'switchLanguage']);

	Route::get('role/permission/{id}', [RoleController::class, 'permission'])->name('role.permission');
	Route::post('role/set_permission', [RoleController::class, 'setPermission'])->name('role.setPermission');
	Route::resource('role', RoleController::class);

	Route::post('importunit', [UnitController::class, 'importUnit'])->name('unit.import');
	Route::post('unit/deletebyselection', [UnitController::class, 'deleteBySelection']);
	Route::get('unit/lims_unit_search', [UnitController::class, 'limsUnitSearch'])->name('unit.search');
	Route::resource('unit', UnitController::class);

	Route::post('category/import', [CategoryController::class, 'import'])->name('category.import');
	Route::post('category/deletebyselection', [CategoryController::class, 'deleteBySelection']);
	Route::post('category/category-data', [CategoryController::class, 'categoryData']);
	Route::resource('/category', CategoryController::class);

	Route::post('importbrand', [BrandController::class, 'importBrand'])->name('brand.import');
	Route::post('brand/deletebyselection', [BrandController::class, 'deleteBySelection']);
	Route::get('brand/lims_brand_search', [BrandController::class, 'limsBrandSearch'])->name('brand.search');
	Route::resource('brand', BrandController::class);

	Route::post('importsupplier', [SupplierController::class, 'importSupplier'])->name('supplier.import');
	Route::post('supplier/deletebyselection', [SupplierController::class, 'deleteBySelection']);
	Route::post('suppliers/clear-due', [supplierControllerSupplierController::class, 'clearDue'])->name('supplier.clearDue');
	Route::resource('supplier', SupplierController::class);

	Route::post('importwarehouse', [WarehouseController::class, 'importWarehouse'])->name('warehouse.import');
	Route::post('warehouse/deletebyselection', [WarehouseController::class, 'deleteBySelection']);
	Route::get('warehouse/lims_warehouse_search', [WarehouseController::class, 'limsWarehouseSearch'])->name('warehouse.search');
	Route::resource('warehouse', WarehouseController::class);

	Route::post('importtax', [TaxController::class, 'importTax'])->name('tax.import');
	Route::post('tax/deletebyselection', [TaxController::class, 'deleteBySelection']);
	Route::get('tax/lims_tax_search', [TaxController::class, 'limsTaxSearch'])->name('tax.search');
	Route::resource('tax', TaxController::class);

	//Route::get('products/getbarcode', [ProductController::class, 'getBarcode']);
	Route::post('products/product-data', [ProductController::class, 'productData']);
	Route::get('products/gencode', [ProductController::class, 'generateCode']);
	Route::get('products/search', [ProductController::class, 'search']);
	Route::get('products/saleunit/{id}', [ProductController::class, 'saleUnit']);
	Route::get('products/getdata/{id}/{variant_id}', [ProductController::class, 'getData']);
	Route::get('products/product_warehouse/{id}', [ProductController::class, 'productWarehouseData']);
	Route::post('importproduct', [ProductController::class, 'importProduct'])->name('product.import');
	Route::post('exportproduct', [ProductController::class, 'exportProduct'])->name('product.export');
	Route::get('products/print_barcode', [ProductController::class, 'printBarcode'])->name('product.printBarcode');
	Route::get('products/lims_product_search', [ProductController::class, 'limsProductSearch'])->name('product.search');
	Route::post('products/deletebyselection', [ProductController::class, 'deleteBySelection']);
	Route::post('products/update', [ProductController::class, 'updateProduct']);
	Route::get('products/variant-data/{id}', [ProductController::class, 'variantData']);
	Route::get('products/history', [ProductController::class, 'history'])->name('products.history');
	Route::post('products/sale-history-data', [ProductController::class, 'saleHistoryData']);
	Route::post('products/purchase-history-data', [ProductController::class, 'purchaseHistoryData']);
	Route::post('products/sale-return-history-data', [ProductController::class, 'saleReturnHistoryData']);
	Route::post('products/purchase-return-history-data', [ProductController::class, 'purchaseReturnHistoryData']);
	Route::resource('products', ProductController::class);

	Route::post('importcustomer_group', [CustomerGroupController::class, 'importCustomerGroup'])->name('customer_group.import');
	Route::post('customer_group/deletebyselection', [CustomerGroupController::class, 'deleteBySelection']);
	Route::get('customer_group/lims_customer_group_search', [CustomerGroupController::class, 'limsCustomerGroupSearch'])->name('customer_group.search');
	Route::resource('customer_group', CustomerGroupController::class);

	Route::resource('discount-plans', DiscountPlanController::class);
	Route::resource('discounts', DiscountController::class);
	Route::get('discounts/product-search/{code}', [DiscountController::class, 'productSearch']);

	Route::post('importcustomer', [CustomerController::class, 'importCustomer'])->name('customer.import');
	Route::get('customer/getDeposit/{id}', [CustomerController::class, 'getDeposit']);
	Route::post('customer/add_deposit', [CustomerController::class, 'addDeposit'])->name('customer.addDeposit');
	Route::post('customer/update_deposit', [CustomerController::class, 'updateDeposit'])->name('customer.updateDeposit');
	Route::post('customer/deleteDeposit', [CustomerController::class, 'deleteDeposit'])->name('customer.deleteDeposit');
	Route::post('customer/deletebyselection', [CustomerController::class, 'deleteBySelection']);
	Route::get('customer/lims_customer_search', [CustomerController::class, 'limsCustomerSearch'])->name('customer.search');
	Route::post('customers/clear-due', [CustomerController::class, 'clearDue'])->name('customer.clearDue');
	Route::resource('customer', CustomerController::class);

	Route::post('importbiller', [BillerController::class, 'importBiller'])->name('biller.import');
	Route::post('biller/deletebyselection', [BillerController::class, 'deleteBySelection']);
	Route::get('biller/lims_biller_search', [BillerController::class, 'limsBillerSearch'])->name('biller.search');
	Route::resource('biller', BillerController::class);

	Route::post('sales/sale-data', [SaleController::class, 'saleData']);
	Route::post('sales/sendmail', [SaleController::class, 'sendMail'])->name('sale.sendmail');
	Route::get('sales/sale_by_csv', [SaleController::class, 'saleByCsv']);
	Route::get('sales/product_sale/{id}', [SaleController::class, 'productSaleData']);
	Route::post('importsale', [SaleController::class, 'importSale'])->name('sale.import');
	Route::get('pos', [SaleController::class, 'posSale'])->name('sale.pos');
	Route::get('sales/lims_sale_search', [SaleController::class, 'limsSaleSearch'])->name('sale.search');
	Route::get('sales/lims_product_search', [SaleController::class, 'limsProductSearch'])->name('product_sale.search');
	Route::get('sales/getcustomergroup/{id}', [SaleController::class, 'getCustomerGroup'])->name('sale.getcustomergroup');
	Route::get('sales/getproduct/{id}', [SaleController::class, 'getProduct'])->name('sale.getproduct');
	Route::get('sales/getproduct/{category_id}/{brand_id}', [SaleController::class, 'getProductByFilter']);
	Route::get('sales/getfeatured', [SaleController::class, 'getFeatured']);
	Route::get('sales/allproduct', [SaleController::class, 'allProduct']);
	Route::get('sales/get_gift_card', [SaleController::class, 'getGiftCard']);
	Route::get('sales/paypalSuccess', [SaleController::class, 'paypalSuccess']);
	Route::get('sales/paypalPaymentSuccess/{id}', [SaleController::class, 'paypalPaymentSuccess']);
	Route::get('sales/gen_invoice/{id}', [SaleController::class, 'genInvoice'])->name('sale.invoice');
	Route::post('sales/add_payment', [SaleController::class, 'addPayment'])->name('sale.add-payment');
	Route::get('sales/getpayment/{id}', [SaleController::class, 'getPayment'])->name('sale.get-payment');
	Route::post('sales/updatepayment', [SaleController::class, 'updatePayment'])->name('sale.update-payment');
	Route::post('sales/deletepayment', [SaleController::class, 'deletePayment'])->name('sale.delete-payment');
	Route::get('sales/{id}/create', [SaleController::class, 'createSale']);
	Route::post('sales/deletebyselection', [SaleController::class, 'deleteBySelection']);
	Route::get('sales/print-last-reciept', [SaleController::class, 'printLastReciept'])->name('sales.printLastReciept');
	Route::get('sales/today-sale', [SaleController::class, 'todaySale']);
	Route::get('sales/today-profit/{warehouse_id}', [SaleController::class, 'todayProfit']);
	Route::get('sales/check-discount', [SaleController::class, 'checkDiscount']);
	Route::get('sales/add_payment_cicilan', [SaleController::class, 'formAddPaymentCicilan'])->name('sale.add-payment-cicilan');
	Route::post('sales/add_payment_cicilan', [SaleController::class, 'addPaymentCicilan'])->name('sale.add-payment-cicilan');

	Route::resource('sales', SaleController::class);

	Route::get('delivery', [DeliveryController::class, 'index'])->name('delivery.index');
	Route::get('delivery/product_delivery/{id}', [DeliveryController::class, 'productDeliveryData']);
	Route::get('delivery/create/{id}', [DeliveryController::class, 'create']);
	Route::post('delivery/store', [DeliveryController::class, 'store'])->name('delivery.store');
	Route::post('delivery/sendmail', [DeliveryController::class, 'sendMail'])->name('delivery.sendMail');
	Route::get('delivery/{id}/edit', [DeliveryController::class, 'edit']);
	Route::post('delivery/update', [DeliveryController::class, 'update'])->name('delivery.update');
	Route::post('delivery/deletebyselection', [DeliveryController::class, 'deleteBySelection']);
	Route::post('delivery/delete/{id}', [DeliveryController::class, 'delete'])->name('delivery.delete');

	Route::post('quotations/quotation-data', [QuotationController::class, 'quotationData'])->name('quotations.data');
	Route::get('quotations/product_quotation/{id}', [QuotationController::class, 'productQuotationData']);
	Route::get('quotations/lims_product_search', [QuotationController::class, 'limsProductSearch'])->name('product_quotation.search');
	Route::get('quotations/getcustomergroup/{id}', [QuotationController::class, 'getCustomerGroup'])->name('quotation.getcustomergroup');
	Route::get('quotations/getproduct/{id}', [QuotationController::class, 'getProduct'])->name('quotation.getproduct');
	Route::get('quotations/{id}/create_sale', [QuotationController::class, 'createSale'])->name('quotation.create_sale');
	Route::get('quotations/{id}/create_purchase', [QuotationController::class, 'createPurchase'])->name('quotation.create_purchase');
	Route::post('quotations/sendmail', [QuotationController::class, 'sendMail'])->name('quotation.sendmail');
	Route::post('quotations/deletebyselection', [QuotationController::class, 'deleteBySelection']);
	Route::resource('quotations', QuotationController::class);

	Route::post('purchases/purchase-data', [PurchaseController::class, 'purchaseData'])->name('purchases.data');
	Route::get('purchases/product_purchase/{id}', [PurchaseController::class, 'productPurchaseData']);
	Route::get('purchases/lims_product_search', [PurchaseController::class, 'limsProductSearch'])->name('product_purchase.search');
	Route::post('purchases/add_payment', [PurchaseController::class, 'addPayment'])->name('purchase.add-payment');
	Route::get('purchases/add_payment_cicilan', [PurchaseController::class, 'formAddPaymentCicilan'])->name('purchase.add-payment-cicilan');
	Route::post('purchases/add_payment_cicilan', [PurchaseController::class, 'addPaymentCicilan'])->name('purchase.add-payment-cicilan');
	Route::get('purchases/getpayment/{id}', [PurchaseController::class, 'getPayment'])->name('purchase.get-payment');
	Route::post('purchases/updatepayment', [PurchaseController::class, 'updatePayment'])->name('purchase.update-payment');
	Route::post('purchases/deletepayment', [PurchaseController::class, 'deletePayment'])->name('purchase.delete-payment');
	Route::get('purchases/purchase_by_csv', [PurchaseController::class, 'purchaseByCsv']);
	Route::post('importpurchase', [PurchaseController::class, 'importPurchase'])->name('purchase.import');
	Route::post('purchases/deletebyselection', [PurchaseController::class, 'deleteBySelection']);
	Route::resource('purchases', PurchaseController::class);

	Route::post('transfers/transfer-data', [TransferController::class, 'transferData'])->name('transfers.data');
	Route::get('transfers/product_transfer/{id}', [TransferController::class, 'productTransferData']);
	Route::get('transfers/transfer_by_csv', [TransferController::class, 'transferByCsv']);
	Route::post('importtransfer', [TransferController::class, 'importTransfer'])->name('transfer.import');
	Route::get('transfers/getproduct/{id}', [TransferController::class, 'getProduct'])->name('transfer.getproduct');
	Route::get('transfers/lims_product_search', [TransferController::class, 'limsProductSearch'])->name('product_transfer.search');
	Route::post('transfers/deletebyselection', [TransferController::class, 'deleteBySelection']);
	Route::resource('transfers', TransferController::class);

	Route::get('qty_adjustment/getproduct/{id}', [AdjustmentController::class, 'getProduct'])->name('adjustment.getproduct');
	Route::get('qty_adjustment/lims_product_search', [AdjustmentController::class, 'limsProductSearch'])->name('product_adjustment.search');
	Route::post('qty_adjustment/deletebyselection', [AdjustmentController::class, 'deleteBySelection']);
	Route::resource('qty_adjustment', AdjustmentController::class);

	Route::post('return-sale/return-data', [ReturnController::class, 'returnData']);
	Route::get('return-sale/getcustomergroup/{id}', [ReturnController::class, 'getCustomerGroup'])->name('return-sale.getcustomergroup');
	Route::post('return-sale/sendmail', [ReturnController::class, 'sendMail'])->name('return-sale.sendmail');
	Route::get('return-sale/getproduct/{id}', [ReturnController::class, 'getProduct'])->name('return-sale.getproduct');
	Route::get('return-sale/lims_product_search', [ReturnController::class, 'limsProductSearch'])->name('product_return-sale.search');
	Route::get('return-sale/product_return/{id}', [ReturnController::class, 'productReturnData']);
	Route::post('return-sale/deletebyselection', [ReturnController::class, 'deleteBySelection']);
	Route::resource('return-sale', ReturnController::class);

	Route::post('return-purchase/return-data', [ReturnPurchaseController::class, 'returnData']);
	Route::get('return-purchase/getcustomergroup/{id}', [ReturnPurchaseController::class, 'getCustomerGroup'])->name('return-purchase.getcustomergroup');
	Route::post('return-purchase/sendmail', [ReturnPurchaseController::class, 'sendMail'])->name('return-purchase.sendmail');
	Route::get('return-purchase/getproduct/{id}', [ReturnPurchaseController::class, 'getProduct'])->name('return-purchase.getproduct');
	Route::get('return-purchase/lims_product_search', [ReturnPurchaseController::class, 'limsProductSearch'])->name('product_return-purchase.search');
	Route::get('return-purchase/product_return/{id}', [ReturnPurchaseController::class, 'productReturnData']);
	Route::post('return-purchase/deletebyselection', [ReturnPurchaseController::class, 'deleteBySelection']);
	Route::resource('return-purchase', ReturnPurchaseController::class);

	Route::get('report/product_quantity_alert', [ReportController::class, 'productQuantityAlert'])->name('report.qtyAlert');
	Route::get('report/daily-sale-objective', [ReportController::class, 'dailySaleObjective'])->name('report.dailySaleObjective');
	Route::post('report/daily-sale-objective-data', [ReportController::class, 'dailySaleObjectiveData']);
	Route::get('report/product-expiry', [ReportController::class, 'productExpiry'])->name('report.productExpiry');
	Route::get('report/warehouse_stock', [ReportController::class, 'warehouseStock'])->name('report.warehouseStock');
	Route::get('report/daily_sale/{year}/{month}', [ReportController::class, 'dailySale']);
	Route::post('report/daily_sale/{year}/{month}', [ReportController::class, 'dailySaleByWarehouse'])->name('report.dailySaleByWarehouse');
	Route::get('report/monthly_sale/{year}', [ReportController::class, 'monthlySale']);
	Route::post('report/monthly_sale/{year}', [ReportController::class, 'monthlySaleByWarehouse'])->name('report.monthlySaleByWarehouse');
	Route::get('report/daily_purchase/{year}/{month}', [ReportController::class, 'dailyPurchase']);
	Route::post('report/daily_purchase/{year}/{month}', [ReportController::class, 'dailyPurchaseByWarehouse'])->name('report.dailyPurchaseByWarehouse');
	Route::get('report/monthly_purchase/{year}', [ReportController::class, 'monthlyPurchase']);
	Route::post('report/monthly_purchase/{year}', [ReportController::class, 'monthlyPurchaseByWarehouse'])->name('report.monthlyPurchaseByWarehouse');
	Route::get('report/best_seller', [ReportController::class, 'bestSeller']);
	Route::post('report/best_seller', [ReportController::class, 'bestSellerByWarehouse'])->name('report.bestSellerByWarehouse');
	Route::post('report/profit_loss', [ReportController::class, 'profitLoss'])->name('report.profitLoss');
	Route::get('report/product_report', [ReportController::class, 'productReport'])->name('report.product');
	Route::post('report/product_report_data', [ReportController::class, 'productReportData']);
	Route::post('report/purchase', [ReportController::class, 'purchaseReport'])->name('report.purchase');
	Route::post('report/sale_report', [ReportController::class, 'saleReport'])->name('report.sale');
	Route::post('report/sale-report-chart', [ReportController::class, 'saleReportChart'])->name('report.saleChart');
	Route::post('report/payment_report_by_date', [ReportController::class, 'paymentReportByDate'])->name('report.paymentByDate');
	Route::post('report/warehouse_report', [ReportController::class, 'warehouseReport'])->name('report.warehouse');
	Route::post('report/user_report', [ReportController::class, 'userReport'])->name('report.user');
	Route::post('report/customer_report', [ReportController::class, 'customerReport'])->name('report.customer');
	Route::post('report/supplier', [ReportController::class, 'supplierReport'])->name('report.supplier');
	Route::post('report/customer-due-report', [ReportController::class, 'customerDueReportByDate'])->name('report.customerDueByDate');
	Route::post('report/supplier-due-report', [ReportController::class, 'supplierDueReportByDate'])->name('report.supplierDueByDate');

	Route::get('user/profile/{id}', [UserController::class, 'profile'])->name('user.profile');
	Route::put('user/update_profile/{id}', [UserController::class, 'profileUpdate'])->name('user.profileUpdate');
	Route::put('user/changepass/{id}', [UserController::class, 'changePassword'])->name('user.password');
	Route::get('user/genpass', [UserController::class, 'generatePassword']);
	Route::post('user/deletebyselection', [UserController::class, 'deleteBySelection']);
	Route::resource('user', UserController::class);

	Route::get('setting/general_setting', [SettingController::class, 'generalSetting'])->name('setting.general');
	Route::post('setting/general_setting_store', [SettingController::class, 'generalSettingStore'])->name('setting.generalStore');

	Route::get('setting/reward-point-setting', [SettingController::class, 'rewardPointSetting'])->name('setting.rewardPoint');
	Route::post('setting/reward-point-setting_store', [SettingController::class, 'rewardPointSettingStore'])->name('setting.rewardPointStore');

	Route::get('backup', [SettingController::class, 'backup'])->name('setting.backup');
	Route::get('setting/general_setting/change-theme/{theme}', [SettingController::class, 'changeTheme']);
	Route::get('setting/mail_setting', [SettingController::class, 'mailSetting'])->name('setting.mail');
	Route::get('setting/sms_setting', [SettingController::class, 'smsSetting'])->name('setting.sms');
	Route::get('setting/createsms', [SettingController::class, 'createSms'])->name('setting.createSms');
	Route::post('setting/sendsms', [SettingController::class, 'sendSms'])->name('setting.sendSms');
	Route::get('setting/hrm_setting', [SettingController::class, 'hrmSetting'])->name('setting.hrm');
	Route::post('setting/hrm_setting_store', [SettingController::class, 'hrmSettingStore'])->name('setting.hrmStore');
	Route::post('setting/mail_setting_store', [SettingController::class, 'mailSettingStore'])->name('setting.mailStore');
	Route::post('setting/sms_setting_store', [SettingController::class, 'smsSettingStore'])->name('setting.smsStore');
	Route::get('setting/pos_setting', [SettingController::class, 'posSetting'])->name('setting.pos');
	Route::post('setting/pos_setting_store', [SettingController::class, 'posSettingStore'])->name('setting.posStore');
	Route::get('setting/empty-database', [SettingController::class, 'emptyDatabase'])->name('setting.emptyDatabase');

	Route::get('expense_categories/gencode', [ExpenseCategoryController::class, 'generateCode']);
	Route::post('expense_categories/import', [ExpenseCategoryController::class, 'import'])->name('expense_category.import');
	Route::post('expense_categories/deletebyselection', [ExpenseCategoryController::class, 'deleteBySelection']);
	Route::resource('expense_categories', ExpenseCategoryController::class);

	Route::post('expenses/expense-data', [ExpenseController::class, 'expenseData'])->name('expenses.data');
	Route::post('expenses/deletebyselection', [ExpenseController::class, 'deleteBySelection']);
	Route::resource('expenses', ExpenseController::class);

	Route::get('gift_cards/gencode', [GiftCardController::class, 'generateCode']);
	Route::post('gift_cards/recharge/{id}', [GiftCardController::class, 'recharge'])->name('gift_cards.recharge');
	Route::post('gift_cards/deletebyselection', [GiftCardController::class, 'deleteBySelection']);
	Route::resource('gift_cards', GiftCardController::class);

	Route::get('coupons/gencode', [CouponController::class, 'generateCode']);
	Route::post('coupons/deletebyselection', [CouponController::class, 'deleteBySelection']);
	Route::resource('coupons', CouponController::class);
	//accounting routes
	Route::get('accounts/make-default/{id}', [AccountsController::class, 'makeDefault']);
	Route::get('accounts/impor_saldo_awal', [AccountsController::class, 'formSaldoAwal'])->name('accounts.impor_saldo_awal');
	Route::get('accounts/format_saldo_awal', [AccountsController::class, 'formatSaldoAwal'])->name('accounts.format_saldo_awal');
	Route::post('accounts/impor_saldo_awal', [AccountsController::class, 'imporSaldoAwal'])->name('accounts.impor_saldo_awal');
	Route::get('accounts/balancesheet', [AccountsController::class, 'balanceSheet'])->name('accounts.balancesheet');
	Route::post('accounts/account-statement', [AccountsController::class, 'accountStatement'])->name('accounts.statement');
	Route::resource('accounts', AccountsController::class);
	Route::resource('money-transfers', MoneyTransferController::class);

	Route::get('jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
	Route::get('jurnal/create', [JurnalController::class, 'create'])->name('jurnal.create');
	Route::post('jurnal/store', [JurnalController::class, 'store'])->name('jurnal.store');
	Route::get('jurnal/edit/{id}', [JurnalController::class, 'edit'])->name('jurnal.edit');
	Route::get('jurnal/detail/{id}', [JurnalController::class, 'detail'])->name('jurnal.detail');
	Route::get('jurnal/getjenistransaksi', [JurnalController::class, 'getJenisTransaksi'])->name('jurnal.getjenistransaksi');
	Route::delete('jurnal/destroy/{id}', [JurnalController::class, 'destroy'])->name('jurnal.destroy');

	Route::get('tutup_buku', [TutupBukuController::class, 'index'])->name('tutup_buku.index');
	Route::post('tutup_buku/submit', [TutupBukuController::class, 'submit'])->name('tutup_buku.submit');
	//HRM routes
	Route::post('departments/deletebyselection', [DepartmentController::class, 'deleteBySelection']);
	Route::resource('departments', DepartmentController::class);

	Route::post('employees/deletebyselection', [EmployeeController::class, 'deleteBySelection']);
	Route::resource('employees', EmployeeController::class);

	Route::post('payroll/deletebyselection', [PayrollController::class, 'deleteBySelection']);
	Route::resource('payroll', PayrollController::class);

	Route::post('attendance/deletebyselection', [AttendanceController::class, 'deleteBySelection']);
	Route::resource('attendance', AttendanceController::class);

	Route::resource('stock-count', StockCountController::class);
	Route::post('stock-count/finalize', [StockCountController::class, 'finalize'])->name('stock-count.finalize');
	Route::get('stock-count/stockdif/{id}', [StockCountController::class, 'stockDif']);
	Route::get('stock-count/{id}/qty_adjustment', [StockCountController::class, 'qtyAdjustment'])->name('stock-count.adjustment');

	Route::post('holidays/deletebyselection', [HolidayController::class, 'deleteBySelection']);
	Route::get('approve-holiday/{id}', [HolidayController::class, 'approveHoliday'])->name('approveHoliday');
	Route::get('holidays/my-holiday/{year}/{month}', [HolidayController::class, 'myHoliday'])->name('myHoliday');
	Route::resource('holidays', HolidayController::class);

	Route::get('cash-register', [CashRegisterController::class, 'index'])->name('cashRegister.index');
	Route::get('cash-register/check-availability/{warehouse_id}', [CashRegisterController::class, 'checkAvailability'])->name('cashRegister.checkAvailability');
	Route::post('cash-register/store', [CashRegisterController::class, 'store'])->name('cashRegister.store');
	Route::get('cash-register/getDetails/{id}', [CashRegisterController::class, 'getDetails']);
	Route::get('cash-register/showDetails/{warehouse_id}', [CashRegisterController::class, 'showDetails']);
	Route::post('cash-register/close', [CashRegisterController::class, 'close'])->name('cashRegister.close');

	Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
	Route::post('notifications/store', [NotificationController::class, 'store'])->name('notifications.store');
	Route::get('notifications/mark-as-read', [NotificationController::class, 'markAsRead']);

	Route::resource('currency', CurrencyController::class);

	Route::get('/home', [HomeController::class, 'index'])->name('home');
	Route::get('my-transactions/{year}/{month}', [HomeController::class, 'myTransaction']);
	//laporan keuangan
	Route::get('laporan_keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan_keuangan');
	Route::get('laporan_keuangan/jurnal', [LaporanKeuanganController::class, 'jurnal'])->name('laporan_keuangan.jurnal');
	Route::get('laporan_keuangan/buku_besar', [LaporanKeuanganController::class, 'bukuBesar'])->name('laporan_keuangan.buku_besar');
	Route::get('laporan_keuangan/neraca', [LaporanKeuanganController::class, 'neraca'])->name('laporan_keuangan.neraca');
	Route::get('laporan_keuangan/neraca_saldo', [LaporanKeuanganController::class, 'neracaSaldo'])->name('laporan_keuangan.neraca_saldo');
	Route::get('laporan_keuangan/rugi_laba', [LaporanKeuanganController::class, 'rugiLaba'])->name('laporan_keuangan.rugi_laba');
	Route::get('laporan_keuangan/arus_kas', [LaporanKeuanganController::class, 'arusKas'])->name('laporan_keuangan.arus_kas');
	Route::get('laporan_keuangan/inventaris', [LaporanKeuanganController::class, 'inventaris'])->name('laporan_keuangan.inventaris');
});
