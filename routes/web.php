<?php

use App\Http\Controllers\Admin\VoucherRequestController;
use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Common\DashboardController;
use App\Http\Controllers\Common\ProfileController;
use App\Http\Controllers\Common\UserNotificationController;
use App\Http\Controllers\Lawyer\ClientController;
use App\Http\Controllers\Lawyer\FirmAccountController;
use App\Http\Controllers\Lawyer\ClientAccountController;
use App\Http\Controllers\Lawyer\AccountReportingController;
use App\Http\Controllers\Lawyer\CaseFileController;
use App\Http\Controllers\Lawyer\ClaimVoucherController;
use App\Http\Controllers\Lawyer\ConflictReportController;
use App\Http\Controllers\Lawyer\DisbursementItemController;
use App\Http\Controllers\Lawyer\DisbursementItemTypeController;
use App\Http\Controllers\Lawyer\InvoiceController;
use App\Http\Controllers\Lawyer\InvoicePaymentController;
use App\Http\Controllers\Lawyer\QuotationController;
use App\Http\Controllers\Lawyer\OperationalCostController;
use App\Http\Controllers\Lawyer\QuotationPaymentController;
use App\Http\Controllers\Lawyer\ReceiptController;
use App\Http\Controllers\Lawyer\AccountingManagementController;
use App\Http\Controllers\Lawyer\OperationalCostTypesController;

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

// Route::group(['middleware' => 'guest'],function() {
Route::get('/', [LoginController::class, 'index']);
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);

Route::get('register', [RegisterController::class, 'index'])->name('register');
Route::post('register', [RegisterController::class, 'registerNewAccount']);

Route::get('forgotpassword', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forgotpassword');
Route::post('forgotpassword', [ForgotPasswordController::class, 'submitForgetPasswordForm']);
Route::get('resetpassword/{token}', [ForgotPasswordController::class, 'showResetPaswordForm'])->name('forgotpassword');
Route::post('resetpassword', [ForgotPasswordController::class, 'submitResetPasswordForm']);
// });


Route::group(['middleware' => 'auth'], function () {
    // Ajax call routes
    Route::get('/notifications', [UserNotificationController::class, 'index']);
    Route::get('/notifications/mark-all-as-read', [UserNotificationController::class, 'markAllAsRead']);
    Route::get('/bank-accounts', [BankAccountController::class, 'fetchBankAccounts']);
    Route::get('/bank-accounts/{bank_account}', [BankAccountController::class, 'fetchBankAccountDetails']);

    //Common route
    Route::get('/', [DashboardController::class, 'redirectToDashboard']);

    //Routes for Administrator ONLY
    Route::group(['middleware' => 'check.role:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/', [DashboardController::class, 'indexAdmin']);
        Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'indexAdmin'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'editAdmin'])->name('profile.edit');
        Route::post('/profile/update', [ProfileController::class, 'updateAdmin'])->name('profile.update');

        // Voucher Request Approvals
        Route::post('/voucher-requests/{voucher_request}/approve', [VoucherRequestController::class, 'approveVoucher']);
        Route::post('/voucher-requests/{voucher_request}/reject', [VoucherRequestController::class, 'rejectVoucher']);
        Route::resource('voucher-requests', VoucherRequestController::class)->only('index', 'show');

        // Employees
        Route::resource('users', UserController::class)->except('show');

        // Bank Accounts
        Route::resource('bank-accounts', BankAccountController::class);

        // Company Profile
        Route::singleton('company', CompanyController::class)->except('destroy');
    });

    //Routes for Lawyer ONLY
    Route::group(['middleware' => 'check.role:lawyer', 'prefix' => 'lawyer', 'as' => 'lawyer.'], function () {
        Route::get('/', [DashboardController::class, 'indexLawyer']);
        Route::get('/dashboard', [DashboardController::class, 'indexLawyer']);
        Route::get('/profile', [ProfileController::class, 'indexLawyer']);
        Route::get('/notifications', [UserNotificationController::class, 'indexLawyer'])->name('notifications');
        Route::post('/disbursement-item-type', DisbursementItemTypeController::class);

        // Client Profiles
        Route::get('client/update', [ClientController::class, 'update']);
        Route::get('client/{client_id}/edit', [ClientController::class, 'edit']);
        Route::get('client/{client_id}/view', [ClientController::class, 'view']);
        Route::resource('client', ClientController::class);

        Route::resource('case-files', CaseFileController::class);

        // FIRM ACCOUNTS    
        Route::get('firm-accounts/{account_number}/detail', [FirmAccountController::class, 'detail']);
        Route::post('firm-accounts/update', [FirmAccountController::class, 'update']);
        Route::get('firm-accounts/{account_number}/{transaction_type}/detail', [FirmAccountController::class, 'detailFilter']);
        Route::get('firm-accounts/{account_number}/create', [FirmAccountController::class, 'create']);
        Route::get('firm-accounts/{account_number}/{selected_item}/view', [FirmAccountController::class, 'view']);
        Route::get('firm-accounts/{account_number}/{selected_item}/edit', [FirmAccountController::class, 'edit']);
        Route::resource('firm-accounts', FirmAccountController::class);

        // DOWNLOAD UPLOADED FILE
        Route::get('/firm-account/download/{id}', [FirmAccountController::class, 'downloadFile'])->name('lawyer.firm-account.download');
        Route::get('/client-account/download/{id}', [ClientAccountController::class, 'downloadFile'])->name('lawyer.client-account.download');
        Route::get('/costs_item/download/{id}', [OperationalCostController::class, 'downloadFile'])->name('lawyer.costs_item.download');

        // CLIENT ACCOUNTS
        Route::get('client-accounts/{account_number}/detail', [ClientAccountController::class, 'detail']);
        Route::post('client-accounts/update', [ClientAccountController::class, 'update']);
        Route::get('client-accounts/{account_number}/{transaction_type}/detail', [ClientAccountController::class, 'detailFilter']);
        Route::get('client-accounts/{account_number}/create', [ClientAccountController::class, 'create']);
        Route::get('client-accounts/{account_number}/{selected_item}/view', [ClientAccountController::class, 'view']);
        Route::get('client-accounts/{account_number}/{selected_item}/edit', [ClientAccountController::class, 'edit']);
        Route::resource('client-accounts', ClientAccountController::class);

        Route::post('claim-vouchers/{claim_voucher}/submit', [ClaimVoucherController::class, 'submitClaimVoucher']);
        Route::resource('claim-vouchers', ClaimVoucherController::class);

        // OPERATIONAL COST
        // Route::get('operational-cost/create', [OperationalCostController::class, 'create']);
        Route::get('operational-cost/{id}/view', [OperationalCostController::class, 'view']);
        Route::post('operational-cost/update', [OperationalCostController::class, 'update']);
        Route::get('operational-cost/{id}/edit', [OperationalCostController::class, 'edit']);
        Route::resource('operational-cost', OperationalCostController::class);
        Route::post('operational-cost-types', [OperationalCostTypesController::class, 'store'])
            ->name('operational-cost-types.store');

        // BALANCE SHEET
        Route::get('accounting-management/cash', [AccountingManagementController::class, 'cash_flow']);
        Route::get('accounting-management/balance', [AccountingManagementController::class, 'balance_sheet']);
        Route::get('accounting-management/profit', [AccountingManagementController::class, 'profit_and_loss']);
        Route::resource('accounting-management', AccountingManagementController::class);

        Route::scopeBindings()->prefix('/case-files/{case_file}')->group(function () {
            Route::post('/resolve-no-conflict', [CaseFileController::class, 'resolveNoConflict']);

            // Conflict Reports
            Route::resource('/conflict-reports', ConflictReportController::class)->only(['index', 'store']);

            // Quotation/Estimate
            Route::prefix('/quotation')->group(function () {
                Route::get('/pdf', [QuotationController::class, 'downloadPdf']);
                Route::post('/email-quotation', [QuotationController::class, 'emailQuotation']);
                Route::post('/mark-sent', [QuotationController::class, 'markSent']);
                Route::post('/save-payment', QuotationPaymentController::class);
            });
            Route::singleton('quotation', QuotationController::class)->creatable();

            Route::group(['middleware' => 'quotation.paid'], function () {
                Route::group(['prefix' => '/invoices/{invoice}'], function () {
                    Route::post('set-open', [InvoiceController::class, 'setOpen']);
                    Route::post('email-invoice', [InvoiceController::class, 'emailInvoice']);
                    Route::post('mark-sent', [InvoiceController::class, 'markSent']);
                    Route::get('pdf', [InvoiceController::class, 'downloadPdf']);

                    // Payment
                    Route::get('/payment/receipt', [InvoicePaymentController::class, 'downloadReceipt']);
                    Route::singleton('payment', InvoicePaymentController::class)->creatable()->only('create', 'store');

                    // Receipt
                    Route::get('receipt/pdf', [ReceiptController::class, 'downloadPdf']);
                    Route::post('receipt/mark-sent', [ReceiptController::class, 'markSent']);
                    Route::post('receipt/email-receipt', [ReceiptController::class, 'emailReceipt']);
                    Route::singleton('receipt', ReceiptController::class)->creatable();
                });

                Route::group(['prefix' => '/disbursement-items/{disbursement_item}'], function () {
                    Route::get('receipt', [DisbursementItemController::class, 'downloadReceipt']);
                });

                Route::resource('disbursement-items', DisbursementItemController::class);
                Route::resource('invoices', InvoiceController::class);
            });
        });
    });
});
