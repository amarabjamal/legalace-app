<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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

        User::create([
            'name' => $request->name,
            'identification_num' => $request->identification_num,
            'employee_id' => $request->employee_id,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('user.index')->with('message', 'Successfully added new user account.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Admin/User/Edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'identification_num' => $request->identification_num,
            'employee_id' => $request->employee_id,
            'email' => $request->email,
        ]);

        return redirect()->route('user.index')->with('message', 'Successfully added new user account.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //Add conditional checking before delete
        $user->delete();   

        return redirect()->route('user.index')->with('message', 'Successfully deleted the user account.');
    }
}
