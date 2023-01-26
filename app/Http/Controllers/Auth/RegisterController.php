<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\IDType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function index()
    {
        $idTypes = IDType::all();
        return Inertia::render('Auth/Register', [
            "idTypes" => $idTypes,
        ]);
    }

    public function registerNewAccount(Request $request) {
        //dd($request);
        // validate the request
        // $attributes = FacadesRequest::validate([
        //     'name' => 'required',
        //     'email' => ['required', 'email'],
        //     'password' => 'required',
        // ]);

        // validate the request
        $company_attributes = FacadesRequest::validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'id_type' => 'required',
            'id_num' => 'required',
            'employee_id' => 'required',
            'contact_num' => 'required',
            'birthdate' => 'required',
            'company_name' => 'required',
            'reg_no' => 'required',
            'address' => 'required',
            'company_email' => ['required', 'email'],
            'website' => 'required',
            'password' => 'required',
        ]);

        dd($company_attributes);

        //Validate user
        $errors = array();

        DB::beginTransaction();

        try {
            $companyID = DB::table('companies')->insertGetId([

            ]);

            if($companyID != null) {
                $isSucceed = DB::table('users')->insert([

                ]);
            }

            if($isSucceed) {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        // // create the user
        // User::create($attributes);
        // redirect
        // return redirect('/login');
    }
}
