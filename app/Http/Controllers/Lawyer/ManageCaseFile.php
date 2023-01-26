<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\CaseFile;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ManageCaseFile extends Controller
{
    public function index()
    {
        $caseFiles = CaseFile::where('created_by', '=', Auth::id())->get(['id', 'matter', 'type', 'file_no', 'no_conflict_checked', 'client_id']);
        return Inertia::render('Lawyer/CaseFile/Index', [
            'case_files' => $caseFiles,
        ]);
    }

    public function show($id) {
        $caseFile = CaseFile::find($id);
        return Inertia::render('Lawyer/CaseFile/Show', [
            'case_file' => $caseFile,
        ]);   
    }

    public function create()
    {
        $lawyerRoleID = DB::table('roles')->select('id')->where('slug', 'lawyer');
        $userRole = DB::table('user_role')->select('user_id')->whereIn('role_id', $lawyerRoleID);
        $lawyers = DB::table('users')
                        ->where('company_id', Auth::user()->company_id)
                        ->whereIn('id', $userRole)
                        ->orderBy('name')
                        ->get(['id', 'name']);
        $users = DB::table('users')->select('id')->where('company_id', Auth::user()->company_id);
        $clients = DB::table('clients')
                        ->whereIn('created_by', $users)
                        ->get(['id', 'name']);

        return Inertia::render('Lawyer/CaseFile/Create', [
            'clients' => $clients,
            'lawyers' => $lawyers,
        ]);
    }

    public function store(Request $request)
    {
        $validatedAttributes = $request->validate([
            'matter' => ['required'],
            'type' => ['required'],
            'file_no' => ['required'],
            'client_id' => ['required'],
        ]);

        CaseFile::create([
            'matter' => $validatedAttributes['matter'],
            'type' => $validatedAttributes['type'],
            'file_no' => $validatedAttributes['file_no'],
            'client_id' => $validatedAttributes['client_id'],
            'no_conflict_checked' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('casefiles.index')->with('message', 'Successfully added case file: ' . $validatedAttributes['file_no']);
    }
}
