<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ManageCompany extends Controller
{

    public function index()
    {
        $companyID = Auth::user()->company_id;
        $company = Company::findOrFail($companyID);

        if ($company == null)
        {
            return redirect()->route('company.create')->with('message', 'You have not configure your company profile yet.');
        }

        return Inertia::render('Admin/Company/Index', [
            'company' => $company,
        ]);
    }

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

        return redirect()->route('company.index')->with('message', 'Successfully configured your company profile.');
    }

    public function show(Company $company)
    {
        //
    }

    public function edit()
    {
        
        $companyID = Auth::user()->company_id;
        $company = Company::findOrFail($companyID);

        if ($company == null)
        {
            return redirect()->route('company.create')->with('message', 'You have not configure your company profile yet.');
        }

        return Inertia::render('Admin/Company/Edit', [
            'company' => $company,
        ]);
    }

    public function update(Request $request, Company $company)
    {
        //Data validation

        $company->update([
            'name' => $request->name,
            'reg_no' => $request->reg_no,
            'address' => $request->address,
            'email' => $request->email,
            'website' => $request->website,
            'updated_at' => now(),
        ]);

        return redirect()->route('company.index')->with('message', 'Successfully updated your company profile. ' . $company->name);
    }

    public function destroy(Company $company)
    {
        //
    }

    function isCompanyProfileConfigured() {
        $companyID = Auth::user()->company_id;

        $company = Company::findOrFail($companyID);

        if ($company !== null)
        {
            return true;
        }

        return false;
    }
}
