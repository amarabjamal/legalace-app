<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCaseFileRequest;
use App\Http\Requests\UpdateCaseFileRequest;
use App\Models\CaseFile;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ManageCaseFile extends Controller
{
    protected $casefile;
    protected $quotation;

    public function __construct(CaseFile $casefile, Quotation $quotation)
    {
        $this->casefile = $casefile;   
        $this->quotation = $quotation;
    }
    
    public function index()
    {
        
        return Inertia::render('Lawyer/CaseFile/Index', [
            'case_files' => $this->casefile->myCaseFile(),
        ]);
    }

    public function show($id) 
    {

        return Inertia::render('Lawyer/CaseFile/Show', [
            'case_file' => $this->casefile->getCaseFileById($id),
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

    public function store(StoreCaseFileRequest $request)
    {
        $validated = $request->validated();

        CaseFile::create($validated);

        return redirect()->route('lawyer.casefiles.index')->with('message', 'Successfully added case file [' . $validated['file_number'] . ']');
    }

    public function edit(CaseFile $casefile) 
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

        return Inertia::render('Lawyer/CaseFile/Edit', [
            'case_file' => $casefile,
            'clients' => $clients,
            'lawyers' => $lawyers,
        ]);
    }

    public function update(UpdateCaseFileRequest $request,CaseFile $casefile) 
    {
        $validated = $request->validated();

        $casefile->update($validated);

        return redirect()->route('lawyer.casefiles.show', ['casefile' => $casefile->id])->with('successMessage', 'Successfully updated the Case File');
    }
}
