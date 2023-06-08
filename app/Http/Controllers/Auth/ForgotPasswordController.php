<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetLink;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Str;

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

    public function submitForgetPasswordForm(Request $request)  
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);

        $token = Str::random(64);

        try {
            DB::beginTransaction();

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' =>  $token,
                'created_at' => now(),
            ]);

            $data = [
                'token' => $token,
                'reset_password_link' => url('/resetpassword/' . $token),
            ];

            Mail::to($request->email)->send(new PasswordResetLink($data));
            
            DB::commit();

            return back()->with(
                'infoMessage', 'We have e-mailed your password reset link!'
            );

        } catch(Exception $e) {
            DB::rollBack();
            return back()->with(
                'errorMessage', 'Failed to send password reset link via email. Try again.'
            );
        }
    }

    public function showResetPaswordForm($token) 
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
        ]);
    }

    public function submitResetPasswordForm(Request $request) 
    {
        //dd($request);
        
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
            'password_confirmation' => ['required'],
        ]);

        $isValidToken = DB::table('password_resets')
                            ->where([
                                'token' => $request->token,
                            ])
                            ->first();
        
        if(!$isValidToken) {
            return back()->with('errorMessage', 'Invalid token!');
        } else {
            $expireTime = Carbon::parse($isValidToken->created_at);
            if(now()->diffInMinutes($expireTime) > 30) {
                return back()->with('errorMessage', 'Your link has expired.');
            }
        }

        User::where('email', $isValidToken->email)->update(['password' => bcrypt($request->password) ]);

        DB::table('password_resets')->where('email', $isValidToken->email)->delete();

        return back()->with('successMessage', 'Your password has been changed!');
    }

}
