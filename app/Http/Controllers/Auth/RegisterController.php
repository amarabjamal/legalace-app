<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\IDType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $validatedAttributes = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'id_type_id' => ['required', 'exists:id_types,id'],
            'id_number' => ['required', $request->id_type_id == 1 ? 'regex:/^\d{6}-\d{2}-\d{4}$/' : null ],
            'employee_id' => 'required',
            'contact_number' => ['required', 'min:9', 'numeric'],
            'birthdate' => ['required', 'date'],
            'company_name' => 'required',
            'reg_number' => 'required',
            'address' => 'required',
            'company_email' => ['required', 'email'],
            'website' => 'required',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        DB::beginTransaction();

        try {
            $companyID = DB::table('companies')->insertGetId([
                'name' => $validatedAttributes['company_name'],
                'reg_number' => $validatedAttributes['reg_number'],
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
                'id_type_id' => $validatedAttributes['id_type_id'],
                'id_number' => $validatedAttributes['id_number'],
                'employee_id' => $validatedAttributes['employee_id'],
                'contact_number' => $validatedAttributes['contact_number'],
                'birthdate' => $validatedAttributes['birthdate'],
                'company_id' => $companyID,
                'is_active' => true,
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

        return redirect('/login')->with('infoMessage', 'Account has been succesfully registered.');
    }

    public function testInfoMessage() {
        
        return redirect('/login')->with('infoMessage', 'Account has been succesfully registered.');
    }
}
