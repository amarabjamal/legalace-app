<?php

use App\Http\Controllers\Admin\ApproveVoucher;
use App\Http\Controllers\Admin\ManageBankAccount;
use App\Http\Controllers\Admin\ManageCompany;
use App\Http\Controllers\Admin\ManageUsers;
use App\Models\User;
use App\Models\Client;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Common\DashboardController;
use App\Http\Controllers\Common\ProfileController;
use App\Http\Controllers\Lawyer\ClientController;
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

Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->middleware('auth:admin');

Route::get('/register', function () {
    return Inertia::render('Auth/Register');
});
Route::post('/register', function () {
    // validate the request
    $attributes = Request::validate([
        'name' => 'required',
        'email' => ['required', 'email'],
        'password' => 'required',
    ]);
    // create the user
    User::create($attributes);
    // redirect
    return redirect('/login');
});

Route::middleware('admin')->group(function () {
    
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


//Common routes for all users
Route::middleware('is.valid.user')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/profile', [ProfileController::class, 'index']);
});


Route::middleware('lawyer')->group(function () {
    Route::resource('clients', ClientController::class);
});