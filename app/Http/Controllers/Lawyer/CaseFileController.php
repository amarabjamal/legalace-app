<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCaseFileRequest;
use App\Http\Requests\UpdateCaseFileRequest;
use App\Models\CaseFile;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;

class CaseFileController extends Controller
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
        
        return inertia('Lawyer/CaseFile/Index', [
            'filters' => FacadesRequest::all('search'),
            'case_files' => $this->casefile
                ->myFiles()
                ->OrderByDate()
                ->filter(FacadesRequest::only('search'))
                ->paginate(25)
                ->withQueryString()
                ->through(fn ($file) => [
                    'id' => $file->id,
                    'matter' => $file->matter,
                    'type' => $file->type,
                    'file_number' => $file->file_number,
                    'no_conflict_checked' => $file->no_conflict_checked,
                    'client' => $file->client->name,
                ]),
        ]);
    }

    public function show(CaseFile $case_file) 
    {
        $this->authorize('view', $case_file);

        $case_file->client->pluck('name');

        return Inertia::render('Lawyer/CaseFile/Show', [
            'case_file' => $case_file,
        ]);   
    }

    public function create()
    {
        $this->authorize('create', CaseFile::class);

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
        $this->authorize('create', CaseFile::class);

        $validated = $request->validated();

        CaseFile::create($validated);

        return redirect()->route('lawyer.case-files.index')->with('successMessage', 'Successfully added case the file.');
    }

    public function edit(CaseFile $case_file) 
    {
        $this->authorize('update', $case_file);

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
            'case_file' => $case_file,
            'clients' => $clients,
            'lawyers' => $lawyers,
        ]);
    }

    public function update(UpdateCaseFileRequest $request,CaseFile $case_file) 
    {
        $this->authorize('update', $case_file);

        $validated = $request->validated();

        $case_file->update($validated);

        return redirect()->route('lawyer.case-files.show', $case_file)->with('successMessage', 'Successfully updated the file');
    }
}
