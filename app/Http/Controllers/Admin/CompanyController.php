<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CompanyController extends Controller
{

    public function show()
    {
        $company = Company::findOrFail(auth()->user()->company_id);

        return Inertia::render('Admin/Company/Show', [
            'company' => [
                'name' => $company->name,
                'address' => $company->address,
                'email' => $company->email,
                'website' => $company->website,
            ],
        ]);
    }

    public function edit()
    {
        $company = Company::find(auth()->user()->company_id);

        return Inertia::render('Admin/Company/Edit', [
            'company' => [
                'name' => $company->name,
                'address' => $company->address,
                'email' => $company->email,
                'website' => $company->website,
            ],
        ]);
    }

    public function update(UpdateCompanyRequest $request)
    {
        $company = Company::where('id', auth()->user()->company_id);

        try {
            DB::transaction(function () use ($request, $company) {
                $company->update($request->all());
            });
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to update your company.');
        }

        return redirect()->route('admin.company.show')->with('successMessage', 'Successfully updated your company.');
    }
}
