<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\CaseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class DisbursementItemController extends Controller
{
    public function index(CaseFile $casefile) 
    {

        return inertia('Lawyer/DisbursementItem/Index', [
            'case_file' => [ 
                'id' => $casefile->id,
                'file_number' => $casefile->file_number,
            ],
            'filters' => FacadesRequest::all('search'),
            'disbursement_items' => $casefile->disbursementItems()
                ->orderByDate()
                ->filter(FacadesRequest::only('search'))
                ->paginate(25)
                ->withQueryString()
                ->through(fn ($item) => [
                    'id' => $item->id,
                    'date' => $item->date,
                    'item' => $item->item,
                    'desc' => $item->desc,
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'fund_type' => $item->fund_type,
                    'record_type' => $item->recordType ? $item->recordType->only('name') : null,
                ]),
        ]);
    }

    public function create(CaseFile $casefile) 
    {
        return inertia('Lawyer/DisbursementItem/Create', [
            'case_file' => [ 
                'id' => $casefile->id,
                'file_number' => $casefile->file_number,
            ],
        ]);
    }
}
