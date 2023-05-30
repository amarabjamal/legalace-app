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
            return redirect()->route('company.create')->with('infoMessage', 'You have not configure your company profile yet.');
        }

        return Inertia::render('Admin/Company/Index', [
            'company' => $company,
        ]);
    }

    public function edit()
    {
        $company = Company::find(Auth::user()->company_id);

        return Inertia::render('Admin/Company/Edit', [
            'company' => $company,
        ]);
    }

    public function update(Request $request, Company $company)
    {
        //Data validation

        $company->update([
            'name' => $request->name,
            'reg_number' => $request->reg_number,
            'address' => $request->address,
            'email' => $request->email,
            'website' => $request->website,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.company.index')->with('successMessage', 'Successfully updated your company profile.');
    }
}
