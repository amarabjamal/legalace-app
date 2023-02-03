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
        // validate the request
        $validatedAttributes = FacadesRequest::validate([
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

        $errors = array();

        DB::beginTransaction();

        try {
            $companyID = DB::table('companies')->insertGetId([
                'name' => $validatedAttributes['company_name'],
                'reg_no' => $validatedAttributes['reg_no'],
                'address' => $validatedAttributes['address'],
                'email' => $validatedAttributes['company_email'],
                'website' => $validatedAttributes['website'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $userID = DB::table('users')->insertGetId([
                'name' => $validatedAttributes['name'],
                'email' => $validatedAttributes['email'],
                'password' => bcrypt($validatedAttributes['password']),
                'id_types_id' => $validatedAttributes['id_type'],
                'id_num' => $validatedAttributes['id_num'],
                'employee_id' => $validatedAttributes['employee_id'],
                'contact_num' => $validatedAttributes['contact_num'],
                'birthdate' => $validatedAttributes['birthdate'],
                'company_id' => $companyID,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $isSucceed = DB::table('user_role')->insert([
                'user_id' => $userID,
                'role_id' => 1,
            ]);

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

        return redirect('/login')->with('infoMessage', 'Account has been succesfully registered. Please verify your email.');
    }

    public function testInfoMessage() {
        
        return redirect('/login')->with('infoMessage', 'Account has been succesfully registered. Please verify your email.');
    }
}
