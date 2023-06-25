<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\DisbursementItemFundTypeEnum;
use App\Enums\DisbursementItemStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisbursementItemRequest;
use App\Http\Requests\UpdateDisbursementItemRequest;
use App\Models\CaseFile;
use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\CaseFile\DisbursementItem\DisbursementItemType;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelOptions\Options;

class DisbursementItemController extends Controller
{
    public function index(CaseFile $case_file) 
    {

        return inertia('Lawyer/DisbursementItem/Index', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'filters' => FacadesRequest::all('search'),
            'disbursement_items' => $case_file->disbursementItems()
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
                    'status' => DisbursementItem::STATUS[$item->status->value],
                    'fund_type' => DisbursementItem::FUND_TYPE[$item->fund_type->value],
                    'record_type' => $item->recordType ? $item->recordType->only('name') : null,
                ]),
        ]);
    }

    public function create(CaseFile $case_file) 
    {
        return inertia('Lawyer/DisbursementItem/Create', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'fund_types' => Options::forEnum(DisbursementItemFundTypeEnum::class),
            'record_types' => DisbursementItemType::enabled()->get(['id', 'name']),
        ]);
    }

    public function store(StoreDisbursementItemRequest $request, CaseFile $case_file) 
    {
        $data =  $request->all();
        $data['amount'] = Money::of($data['amount'], 'MYR');
        $filePath = '';
        $fileName = '';
        
        try {
            DB::beginTransaction();

            if($request->hasFile('receipt')) {
                $fileName = uniqid('ITEM_RECEIPT_') . '_' . date('Ymd') . '_' .time() . '.' . $request->file('receipt')->extension();
                $filePath = $request->file('receipt')->storeAs(DisbursementItem::RECEIPT_PATH, $fileName, 'public');
            }
            
            $data['receipt'] = $fileName;

            $case_file->disbursementItems()->create($data);

            DB::commit();

            return redirect()->route('lawyer.disbursement-items.index', $case_file)->with('successMessage', 'Successfully saved the new record.');
        } catch (\Exception $e) {
            DB::rollBack();
            if(Storage::exists($filePath))
            {
                Storage::delete($filePath);
            }

            return back()->with('errorMessage', 'Failed: ' . $e->getMessage());
        }
    }

    public function edit(CaseFile $case_file, DisbursementItem $disbursement_item) 
    {
        if($disbursement_item->status !== DisbursementItemStatusEnum::Recorded) {
            return back()->with('warningMessage', 'This item can no longer be edited');
        }

        return inertia('Lawyer/DisbursementItem/Edit', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'disbursement_item' => $disbursement_item->only(['id', 'date', 'record_type_id', 'name', 'description', 'amount', 'fund_type', 'receipt']),
            'fund_types' => Options::forEnum(DisbursementItemFundTypeEnum::class),
            'record_types' => DisbursementItemType::enabled()->get(['id', 'name']),
        ]);
    }

    public function update(
        UpdateDisbursementItemRequest $request, 
        CaseFile $case_file, 
        DisbursementItem $disbursement_item
    ) 
    {
        if($disbursement_item->status !== DisbursementItemStatusEnum::Recorded) {
            return back()->with('warningMessage', 'This item can no longer be edited');
        }
        
        $data =  $request->all();
        $data['amount'] = Money::of($data['amount'], 'MYR');
        $filePath = null;
        
        try {
            DB::beginTransaction();

            if($request->hasFile('receipt')) {
                $fileName = uniqid('ITEM_RECEIPT_') . '_' . date('Ymd') . '_' .time() . '.' . $request->file('receipt')->extension();
                $filePath = $request->file('receipt')->storeAs(DisbursementItem::RECEIPT_PATH, $fileName, 'public');

                $oldFilePath = 'public/'. DisbursementItem::RECEIPT_PATH . '/' . $disbursement_item->receipt;
                if(Storage::exists($oldFilePath))
                {
                    File::delete($oldFilePath);
                }

                $data['receipt'] = $fileName;
            } else {
                unset($data['receipt']);
            }

            $disbursement_item->update($data);

            DB::commit();

            return back()->with('successMessage', 'Successfully updated the record.');
        } catch (\Exception $e) {
            DB::rollBack();
            if(isset($filePath) && Storage::exists($filePath))
            {
                File::delete($filePath);
            }

            return back()->with('errorMessage', 'Failed: ' . $e->getMessage());
        }
    }

    public function show(CaseFile $case_file, DisbursementItem $disbursement_item) 
    {
        return inertia('Lawyer/DisbursementItem/Show', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'disbursement_item' => [
                'id' => $disbursement_item->id,
                'date' => $disbursement_item->formatted_date,
                'record_type' => $disbursement_item->recordType->name,
                'name' => $disbursement_item->name,
                'description' => $disbursement_item->description,
                'amount' => $disbursement_item->amount->formatTo('EN-MY'),
                'fund_type' => DisbursementItem::FUND_TYPE[$disbursement_item->fund_type->value],
                'receipt' => $disbursement_item->receipt,
                'creator' => $disbursement_item->createdBy->id === auth()->id() ? $disbursement_item->createdBy->name . ' (You)' : $disbursement_item->createdBy->name,
            ],
        ]);
    }

    public function destroy(CaseFile $case_file, DisbursementItem $disbursement_item) 
    {
        try {
            if($disbursement_item->isDeletable()) 
            {
                $disbursement_item->delete();

                return redirect()->route('lawyer.disbursement-items.index', $case_file)->with('successMessage', 'Successfully deleted the record.');
            }

            return back()->with('infoMessage', 'You are not allowed to delete this record since it has been drafted for invoicing.');
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Error: failed to delete.');
        }
    }

    public function downloadReceipt(CaseFile $case_file, DisbursementItem $disbursement_item) {
        if(!isset($disbursement_item->receipt)) {
            return abort(404);
        }
        
        $pathToFile = storage_path('app/public/' . DisbursementItem::RECEIPT_PATH . $disbursement_item->receipt);
        
        return response()->file($pathToFile);
    }
}
