<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\DisbursementItemFundTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisbursementItemRequest;
use App\Models\CaseFile;
use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\CaseFile\DisbursementItem\DisbursementItemType;
use Brick\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;
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
                    'status' => $item->status->name,
                    'fund_type' => DisbursementItem::FUND_TYPE[$item->fund_type->value],
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

            if($request->hasFile('receipt')) {
                $fileName = uniqid('receipt_') . '_' . date('Ymd') . '_' .time() . '.' . $request->file('receipt')->getClientOriginalExtension();
                $filePath = $request->file('receipt')->storeAs(DisbursementItem::RECEIPT_PATH, $fileName, 'public');
            }
            
            $data['receipt'] = $fileName;

            $casefile->disbursementItems()->create($data);

            DB::commit();

            return redirect()->route('lawyer.disbursement-items.index', $casefile)->with('successMessage', 'Successfully saved the new record.');
        } catch (\Exception $e) {
            DB::rollBack();
            if(Storage::exists($filePath))
            {
                File::delete($filePath);
            }

            return back()->with('errorMessage', 'Failed: ' . $e->getMessage());
        }
    }
}
