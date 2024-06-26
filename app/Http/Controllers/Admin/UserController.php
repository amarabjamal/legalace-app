<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IDType;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {

        $users = User::where('id', '!=', auth()->id())
            ->orderBy('name')
            ->filter(FacadesRequest::only('search'))
            ->paginate(25)
            ->withQueryString()
            ->through(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'roles' => $user->role_list,
                'employee_id' => $user->employee_id,
                'email' => $user->email,
                'enabled' => $user->is_active,
            ]);

        return Inertia::render('Admin/User/Index', [
            'filters' => FacadesRequest::all('search'),
            'users' => $users,
        ]);
    }

    public function create()
    {
        $id_types = IDType::all();

        return Inertia::render('Admin/User/Create', [
            "id_types" => $id_types,
        ]);
    }

    public function store(Request $request)
    {
        //Validate the request
        $validated = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'id_type_id' => ['required', 'exists:id_types,id'],
            'id_number' => ['required', $request->id_type_id == 1 ? 'regex:/^\d{6}-\d{2}-\d{4}$/' : null, Rule::unique('users')->where(fn ($query) => $query->where('company_id', Auth::user()->company_id)) ],
            'employee_id' => ['required', Rule::unique('users')->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))],
            'contact_number' => ['required', 'min:9', 'numeric'],
            'birthdate' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
            'access_expiry_date' => ['nullable', 'date'],
        ]);

        if($request->isAdmin == true) {
            $isAdmin = true;
        } else {
            $isAdmin = false;
        }

        if($request->isLawyer == true) {
            $isLawyer = true;
        } else {
            $isLawyer = false;
        }

        $user = User::create([
                    'name' => $validated['name'],
                    'email' =>  $validated['email'],
                    'password' => 'defaultpassword',
                    'id_type_id' =>  $validated['id_type_id'],
                    'id_number' =>  $validated['id_number'],
                    'employee_id' =>  $validated['employee_id'],
                    'contact_number' =>  $validated['contact_number'],
                    'birthdate' => $validated['birthdate'],
                    'company_id' => Auth::user()->company_id,
                    'is_active' => $validated['is_active'],
                    'access_expiry_date' => $validated['access_expiry_date'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

        if($isAdmin) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => 1,
            ]);
        }

        if($isLawyer) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => 2,
            ]);
        }

        return redirect()->route('admin.users.index')->with('successMessage', 'Successfully added new employee account.');
    }

    public function edit(User $user)
    {
        $userRoles = $user->userRoles;

        $roles = array();

        foreach($userRoles as $userRole) {
            array_push($roles, $userRole->role->slug);
        }

        $filteredUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'id_type_id' => $user->id_type_id,
            'id_number' => $user->id_number,
            'employee_id' => $user->employee_id,
            'isAdmin' => in_array('admin', $roles),
            'isLawyer' => in_array('lawyer', $roles),
            'contact_number' => $user->contact_number,
            'birthdate' => $user->birthdate,
            'is_active' => (boolean) $user->is_active,
            'access_expiry_date' => $user->access_expiry_date,
        ];

        $id_types = IDType::all();

        return Inertia::render('Admin/User/Edit', [
            'user' => $filteredUser,
            "idTypes" => $id_types,
        ]);
    }

    public function update(Request $request, User $user)
    {
        //Validate the request
        $validated = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->where(fn ($query) => $query->where([['id', '!=' , $request->id]]))],
            'id_type_id' => ['required', 'exists:id_types,id'],
            'id_number' => ['required', $request->id_type_id == 1 ? 'regex:/^\d{6}-\d{2}-\d{4}$/' : null, Rule::unique('users')->where(fn ($query) => $query->where([['company_id', '=' , Auth::user()->company_id], ['id', '!=' , $request->id]])) ],
            'employee_id' => ['required', Rule::unique('users')->where(fn ($query) => $query->where([['company_id', '=' , Auth::user()->company_id], ['id', '!=' , $request->id]])) ], 
            'contact_number' => ['required', 'min:9', 'numeric'],
            'birthdate' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
            'access_expiry_date' => ['nullable', 'date'],
        ]);


        $userRoles = $user->userRoles;
        $roles = array();

        foreach($userRoles as $userRole) {
            array_push($roles, $userRole->role->slug);
        }

        if(in_array('admin', $roles)) {
            $alreadyAdmin = true;
        } else {
            $alreadyAdmin = false;
        }
        
        if(in_array('lawyer', $roles)) {
            $alreadyLawyer = true;
        } else {
            $alreadyLawyer = false;
        }

        if($request->isAdmin == true) {
            $isAdmin = true;
        } else {
            $isAdmin = false;
        }

        if($request->isLawyer == true) {
            $isLawyer = true;
        } else {
            $isLawyer = false;
        }
        
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'id_type_id' => $validated['id_type_id'],
            'id_number' => $validated['id_number'],
            'employee_id' => $validated['employee_id'],
            'contact_number' => $validated['contact_number'],
            'birthdate' => $validated['birthdate'],
            'is_active' => $validated['is_active'],
            'access_expiry_date' => $validated['access_expiry_date'],
            'updated_at' => now(),
        ]);

        if($isAdmin && !$alreadyAdmin) { //Add admin role to the user
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => 1,
            ]);
        } else if(!$isAdmin && $alreadyAdmin) { //Remove admin role from the user
            UserRole::where([
                ['user_id', '=', $user->id],
                ['role_id', '=', 1],
            ])->delete();
        }

        if($isLawyer && !$alreadyLawyer) { //Add lawyer role to the user
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => 2,
            ]);
        } else if(!$isLawyer && $alreadyLawyer) { //Remove lawyer role from the user
            UserRole::where([
                ['user_id', '=', $user->id],
                ['role_id', '=', 2],
            ])->delete();
        }

        return redirect()->route('admin.users.index')->with('successMessage', 'Successfully updated the employee account.');
    }


    public function destroy(User $user)
    {
        //Add conditional checking before delete
        $userRoles = $user->userRoles;
        foreach($userRoles as $userRole) {
            $userRole->delete();
        }
        $user->delete();   

        return redirect()->route('admin.users.index')->with('successMessage', 'Successfully deleted the employee account.');
    }
}
