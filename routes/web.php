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
use App\Http\Controllers\Email\MailController;
use App\Http\Controllers\Lawyer\ClientController;
use App\Http\Controllers\Lawyer\ManageCaseFile;
use App\Http\Controllers\Lawyer\FirmAccountController;
use App\Http\Controllers\Lawyer\ClientAccountController;
use App\Http\Controllers\Lawyer\AccountReportingController;
use App\Http\Controllers\Lawyer\DisbursementItemController;
use App\Http\Controllers\Lawyer\QuotationController;
use App\Http\Controllers\Lawyer\OperationalCostController;
use App\Models\CaseFile;
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

Route::get('/sendmail', [MailController::class, 'index']);
Route::get('/testInfoMessage', [RegisterController::class, 'testInfoMessage']);

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
    //Routes for Administrator ONLY
    Route::group(['middleware' => 'check.role:admin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('/', [DashboardController::class, 'showAdminDashboard']);
        Route::get('/dashboard', [DashboardController::class, 'showAdminDashboard'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'showAdminProfile'])->name('profile');

        Route::resources([
            'users' => ManageUsers::class,
            'bankaccounts' => ManageBankAccount::class,
            'voucherapprovals' => ApproveVoucher::class,
        ]);
    
        Route::resource('company', ManageCompany::class)->except(['show','edit', 'destroy']);
        Route::get('company/edit', [ManageCompany::class, 'edit'])->name('company.edit');
        
        Route::get('/settings', function () {
            $userId = Auth::id();
            return Inertia::render('Admin/Settings', [$userId]);
        });
    });

    //Routes for Lawyer ONLY
    Route::group(['middleware' => 'check.role:lawyer', 'prefix' => 'lawyer', 'as' => 'lawyer.'], function() {
        Route::get('/', [DashboardController::class, 'showLawyerDashboard']);
        Route::get('/dashboard', [DashboardController::class, 'showLawyerDashboard']);
        Route::get('/profile', [ProfileController::class, 'showLawyerProfile']);

        Route::resource('clients', ClientController::class);
        Route::resource('casefiles', ManageCaseFile::class);

        Route::scopeBindings()->prefix('/casefiles/{casefile}')->group(function() {
            Route::prefix('/quotation')->group(function() {
                Route::get('create', [QuotationController::class, 'create'])->name('quotation.create');
                Route::post('/', [QuotationController::class, 'store'])->name('quotation.store');
                Route::get('/', [QuotationController::class, 'edit'])->name('quotation.edit');
                Route::put('/', [QuotationController::class, 'update'])->name('quotation.update');
                Route::get('/pdf', [QuotationController::class, 'viewPDF']);
                Route::get('/email', [QuotationController::class, 'sendEmail']);
            });

            // Route::prefix('/disbursement-items')->group(function() {
            //     Route::get('/', [DisbursementItemController::class, 'index'])->name('disbursement_item.index');
            // });

            Route::resource('disbursement-items', DisbursementItemController::class)->except('show');
        });
        
        Route::get('/getbankaccountdetails/{bankaccount}', [ManageBankAccount::class, 'getBankAccountDetails']);
    });
});