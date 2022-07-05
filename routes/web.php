<?php

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClerkController;

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
Route::post('logout', [LoginController::class, 'destroy'])->middleware('auth');

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    });
    
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    });
    
    Route::get('/lawyers', function () {
        return Inertia::render('Admin/Lawyers/Index', [
            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name
                ]),
            'filters' => Request::only(['search']),
        ]);
    });
    
    Route::get('/lawyers/create', function () {
        return Inertia::render('Admin/Lawyers/Create');
    });

    Route::get('/lawyers/:id/edit', function () {
        return Inertia::render('Admin/Lawyers/Edit');
    });
    
    Route::post('/lawyers', function () {
        // validate the request
        $attributes = Request::validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);
        // create the user
        User::create($attributes);
        // redirect
        return redirect('/lawyers');
    });

    Route::resource('clerks', ClerkController::class)->except('show');
    // Route::get('/clerks', function () {
    //     return Inertia::render('Admin/Clerks');
    // });
    
    Route::get('/company-profile', function () {
        return Inertia::render('Admin/CompanyProfile');
    });
    
    Route::get('/settings', function () {
        return Inertia::render('Admin/Settings');
    });
    
    Route::post('/logout', function () {
        dd('logging out');
    });

});

