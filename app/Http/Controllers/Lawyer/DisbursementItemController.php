<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\CaseFile;
use Illuminate\Http\Request;

class DisbursementItemController extends Controller
{
    public function index(CaseFile $casefile) 
    {
        return inertia('Lawyer/DisbursementItem/Index', [
            'case_file' => [ 
                'id' => $casefile->id,
                'file_number' => $casefile->file_number,
            ],
            'disbursement_items' => '',
        ]);
    }
}
