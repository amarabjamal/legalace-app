<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\DisbursementItemFundTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisbursementItemRequest;
use App\Models\CaseFile;
use App\Models\CaseFile\DisbursementItem\DisbursementItemType;
use Brick\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Spatie\LaravelOptions\Options;

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
                    'name' => $item->name,
                    'desc' => $item->description,
                    'amount' => $item->amount->formatTo('en_MY'),
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
            'fund_types' => Options::forEnum(DisbursementItemFundTypeEnum::class),
            'record_types' => DisbursementItemType::enabled()->get(['id', 'name']),
        ]);
    }

    public function store(StoreDisbursementItemRequest $request, CaseFile $casefile) 
    {
        $data =  $request->all();
        $data['amount'] = Money::of($data['amount'], 'MYR');
        //dd($data);
        try {
            DB::beginTransaction();

            $casefile->disbursementItems()->create($data);

            DB::commit();

            return redirect()->route('lawyer.disbursement-items.index', $casefile)->with('successMessage', 'Successfully saved the new record.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('errorMessage', 'Failed: ' . $e->getMessage());
        }
    }
}
