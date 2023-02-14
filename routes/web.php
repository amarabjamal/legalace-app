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
use App\Http\Controllers\Lawyer\ManageQuotation;
use App\Http\Controllers\Lawyer\OperationalCostController;
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

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:admin');

Route::get('register', [RegisterController::class, 'index'])->name('register');
Route::post('register', [RegisterController::class, 'registerNewAccount']);
Route::get('/testInfoMessage', [RegisterController::class, 'testInfoMessage']);

Route::get('forgotpassword', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forgotpassword');
Route::post('forgotpassword', [ForgotPasswordController::class, 'submitForgetPasswordForm']);
Route::get('resetpassword/{token}', [ForgotPasswordController::class, 'showResetPaswordForm'])->name('forgotpassword');
Route::post('resetpassword', [ForgotPasswordController::class, 'submitResetPasswordForm']);


Route::group(['middleware' => 'auth'], function() {
    //Routes for Administrator ONLY
    Route::group(['middleware' => 'check.role:admin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('/', [DashboardController::class, 'showAdminDashboard']);
        Route::get('/dashboard', [DashboardController::class, 'showAdminDashboard']);
        Route::get('/profile', [ProfileController::class, 'showAdminProfile']);

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
        Route::get('/quotations/{caseFileId}', [ManageQuotation::class, 'generateQuotation'])->name('generate.quotation');
        Route::post('/quotations', [ManageQuotation::class, 'store']);
        Route::get('/quotations/{quotation}/edit', [ManageQuotation::class, 'edit'])->name('edit.quotation');
        Route::put('/quotations/{quotation}', [ManageQuotation::class, 'update']);
    });
});