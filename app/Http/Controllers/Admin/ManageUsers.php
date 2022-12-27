<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ManageUsers extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $users = User::query()
                            ->when($request->input('search'), function ($query, $search) {
                                $query->where('name', 'like', "%{$search}%");
                            })
                            ->orderBy('name')
                            ->paginate(10)
                            ->withQueryString()
                            ->through(fn($user) => [
                                'id' => $user->id,
                                'name' => $user->name,
                                'id_num' => $user->id_num,
                                'employee_id' => $user->employee_id,
                                'email' => $user->email,
                            ]);
        $filters = $request->only(['search']);

        return Inertia::render('Admin/User/Index', [
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/User/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate the request
        $email = User::where('email', $request->email)->first();
        $id_num = User::where('id_num', $request->id_num)->first();
        $employee_id = User::where('employee_id', $request->employee_id)->first();
        $errors = array();

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

        if($email == null && $id_num == null && $employee_id == null) {

            $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => 'defaultpassword',
                        'id_num' => $request->id_num,
                        'employee_id' => $request->employee_id,
                        'contact_num' => $request->contact_num,
                        'birthdate' => $request->birthdate,
                        'company_id' => Auth::user()->company_id,
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

            return redirect()->route('users.index')->with('message', 'Successfully added new user account.');

        } else {
            if($email != null) {
                $errors += ['email' => 'Email already in use.'];
            } 
            if($employee_id != null) {
                $errors += ['employee_id' => 'Employee ID already in use.'];
            } 
            if($id_num != null) {
                $errors += ['id_num' => 'Identification Number already in use.'];
            } 
 
            return back()->withErrors($errors);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $userRoles = $user->userRoles;

        $roles = array();

        foreach($userRoles as $userRole) {
            array_push($roles, $userRole->role->name);
        }

        $filteredUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'id_num' => $user->id_num,
            'employee_id' => $user->employee_id,
            'isAdmin' => in_array('admin', $roles),
            'isLawyer' => in_array('lawyer', $roles),
            'contact_num' => $user->contact_num,
            'birthdate' => $user->birthdate,
        ];
        return Inertia::render('Admin/User/Edit', [
            'user' => $filteredUser,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,User $user)
    {
        //Validate the request
        $email = User::where([
            ['email', '=' , $request->email],
            ['id', '!=' , $user->id],
        ])->first();
        $id_num = User::where([
            ['id_num', '=' , $request->id_num],
            ['id', '!=' , $user->id],
        ])->first();
        $employee_id = User::where([
            ['employee_id', '=' , $request->employee_id],
            ['id', '!=' , $user->id],
        ])->first();
        $errors = array();

        if($email == null && $id_num == null && $employee_id == null) {

            $userRoles = $user->userRoles;
            $roles = array();

            foreach($userRoles as $userRole) {
                array_push($roles, $userRole->role->name);
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
                'name' => $request->name,
                'email' => $request->email,
                'id_num' => $request->id_num,
                'employee_id' => $request->employee_id,
                'contact_num' => $request->contact_num,
                'birthdate' => $request->birthdate,
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
    
            return redirect()->route('users.index')->with('message', 'Successfully updated the user account.');

        } else {
            if($email != null) {
                $errors += ['email' => 'Email already in use.'];
            } 
            if($employee_id != null) {
                $errors += ['employee_id' => 'Employee ID already in use.'];
            } 
            if($id_num != null) {
                $errors += ['id_num' => 'Identification Number already in use.'];
            } 
 
            return back()->withErrors($errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //Add conditional checking before delete
        $userRoles = $user->userRoles;
        foreach($userRoles as $userRole) {
            $userRole->delete();
        }
        $user->delete();   

        return redirect()->route('users.index')->with('message', 'Successfully deleted the user account.');
    }
}
