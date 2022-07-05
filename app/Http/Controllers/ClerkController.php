<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Clerk;
use Illuminate\Http\Request;


class ClerkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Admin/Clerks/Index', [
            'clerks' => Clerk::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($clerk) => [
                    'id' => $clerk->id,
                    'name' => $clerk->name,
                    'identification_num' => $clerk->identification_num,
                    'employee_id' => $clerk->employee_id,
                    'email' => $clerk->email,
                ]),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Clerks/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate the request

        Clerk::create([
            'name' => $request->name,
            'identification_num' => $request->identification_num,
            'employee_id' => $request->employee_id,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('clerks.index')->with('message', 'Successfully added new clerk account.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clerk  $clerk
     * @return \Illuminate\Http\Response
     */
    public function show(Clerk $clerk)
    {
        dd('shoow');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Clerk  $clerk
     * @return \Illuminate\Http\Response
     */
    public function edit(Clerk $clerk)
    {
        return Inertia::render('Admin/Clerks/Edit', [
            'clerk' => $clerk
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clerk  $clerk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clerk $clerk)
    {
        $clerk->update([
            'name' => $request->name,
            'identification_num' => $request->identification_num,
            'employee_id' => $request->employee_id,
            'email' => $request->email,
        ]);

        return redirect('/clerks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clerk  $clerk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clerk $clerk)
    {
        $clerk->delete();   

        return redirect('/clerks');
    }
}
