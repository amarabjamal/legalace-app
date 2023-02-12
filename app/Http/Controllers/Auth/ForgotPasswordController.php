<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showForgetPasswordForm()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function submitForgetPasswordForm() 
    {
        
    }

    public function showResetPaswordForm() 
    {
        
    }

    public function submitResetPasswordForm() 
    {
        
    }

}
