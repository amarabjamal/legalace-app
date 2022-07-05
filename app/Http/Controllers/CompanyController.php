<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Boolean;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();

        $companyProfile = Company::where('user_id', '=', $userId)->get();

        if (sizeof($companyProfile) < 1)
        {
            return redirect()->route('company-profile.create')->with('message', 'You have not configure your company profile yet.');
        }

        return Inertia::render('Admin/Company/Index', [
            'company_profile' => $companyProfile[0]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId = Auth::id();

        $companyProfile = Company::where('user_id', '=', $userId)->get();

        if (sizeof($companyProfile) < 1)
        {
            return Inertia::render('Admin/Company/Create');
        }
        
        return redirect()->route('company-profile.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Company::create([
            'name' => $request->name,
            'registration_no' => $request->registration_num,
            'address' => $request->address,
            'email' => $request->email,
            'website' => $request->website,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('company-profile.index')->with('message', 'Successfully configured your company profile.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $userId = Auth::id();

        $companyProfile = Company::where('user_id', '=', $userId)->get();

        if (sizeof($companyProfile) < 1)
        {
            return redirect()->route('company-profile.create')->with('message', 'You have not configure your company profile yet.');
        }

        return Inertia::render('Admin/Company/Edit', [
            'company_profile' => $companyProfile[0]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $userId = Auth::id();

        $companyProfile = Company::where('user_id', $userId)->update([
            'name' => $request->name,
            'registration_no' => $request->registration_num,
            'address' => $request->address,
            'email' => $request->email,
            'website' => $request->website,
        ]);
        // $company->update([
        //     'name' => $request->name,
        //     'registration_no' => $request->registration_num,
        //     'address' => $request->address,
        //     'email' => $request->email,
        //     'website' => $request->website,
        // ]);

        return redirect()->route('company-profile.index')->with('message', 'Successfully updated your company profile.' . $company->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }

    function isCompanyProfileConfigured() {
        $userId = Auth::id();

        $companyProfile = Company::where('user_id', '=', $userId)->get();

        if (sizeof($companyProfile) >= 1)
        {
            return true;
        }

        return false;
    }
}
