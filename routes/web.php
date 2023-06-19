<?php

use App\Http\Controllers\Admin\ApproveVoucher;
use App\Http\Controllers\Admin\ManageBankAccount;
use App\Http\Controllers\Admin\ManageCompany;
use App\Http\Controllers\Admin\ManageUsers;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Inertia\Inertia;
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
use App\Http\Controllers\Lawyer\DisbursementItemController;
use App\Http\Controllers\Lawyer\InvoiceController;
use App\Http\Controllers\Lawyer\InvoicePaymentController;
use App\Http\Controllers\Lawyer\QuotationController;
use App\Http\Controllers\Lawyer\OperationalCostController;
use App\Http\Controllers\Lawyer\ReceiptController;
use Illuminate\Support\Facades\Auth;

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


Route::group(['middleware' => 'auth'], function() {
    // Ajax call routes
    Route::get('/notifications', [UserNotificationController::class, 'index']);
    Route::get('/notifications/mark-all-as-read', [UserNotificationController::class, 'markAllAsRead']);
    Route::get('/bank-accounts', [ManageBankAccount::class, 'fetchBankAccounts']);
    Route::get('/bank-accounts/{bank_account}', [ManageBankAccount::class, 'fetchBankAccountDetails']);

    //Routes for Administrator ONLY
    Route::group(['middleware' => 'check.role:admin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('/', [DashboardController::class, 'indexAdmin']);
        Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'indexAdmin'])->name('profile');

        // Voucher Request Approvals
        Route::post('/voucher-requests/{voucher_request}/approve', [ApproveVoucher::class, 'approveVoucher']);
        Route::resource('voucher-requests', ApproveVoucher::class)->only('index','show');

        // Employees
        Route::resource('users', ManageUsers::class)->except('show');

        // Bank Accounts
        Route::resource('bank-accounts', ManageBankAccount::class)->except('show');
    
        // Company Profile
        Route::resource('company', ManageCompany::class)->except(['show','edit', 'destroy']);
        Route::get('company/edit', [ManageCompany::class, 'edit'])->name('company.edit');
    });

    //Routes for Lawyer ONLY
    Route::group(['middleware' => 'check.role:lawyer', 'prefix' => 'lawyer', 'as' => 'lawyer.'], function() {
        Route::get('/', [DashboardController::class, 'indexLawyer']);
        Route::get('/dashboard', [DashboardController::class, 'indexLawyer']);
        Route::get('/profile', [ProfileController::class, 'indexLawyer']);
        Route::get('/notifications', [UserNotificationController::class, 'indexLawyer'])->name('notifications');

        
        Route::resource('clients', ClientController::class);
        
        Route::resource('case-files', CaseFileController::class);
        
        Route::post('claim-vouchers/{claim_voucher}/submit', [ClaimVoucherController::class, 'submitClaimVoucher']);
        Route::resource('claim-vouchers', ClaimVoucherController::class);

        Route::scopeBindings()->prefix('/case-files/{case_file}')->group(function() {
            // Quotation/Estimate
            Route::prefix('/quotation')->group(function() {
                Route::get('/pdf', [QuotationController::class, 'downloadPdf']);
                Route::get('/email', [QuotationController::class, 'emailQuotation']);
            });
            Route::singleton('quotation', QuotationController::class)->creatable();

            // Invoices
            Route::prefix('/invoices/{invoice}')->group(function() {
                Route::post('set-open', [InvoiceController::class, 'setOpen']);
                Route::post('email-invoice', [InvoiceController::class, 'emailInvoice']);
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

            Route::resource('disbursement-items', DisbursementItemController::class);
            Route::resource('invoices', InvoiceController::class);
        });
        
        Route::get('/getbankaccountdetails/{bank_account}', [ManageBankAccount::class, 'getBankAccountDetails']);
    });
});