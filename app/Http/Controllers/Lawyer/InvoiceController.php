<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\DisbursementItemStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\CaseFile;
use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\CaseFile\Invoices\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class InvoiceController extends Controller
{
    public function index(CaseFile $case_file)
    {
        return inertia('Lawyer/Invoice/Index', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'filters' => FacadesRequest::all('search'),
            'invoices' => $case_file->invoices()
                ->filter(FacadesRequest::only('search'))
                ->paginate(25)
                ->withQueryString()
                ->through(fn ($invoice) => [
                    'created_at' => $invoice->created_at,
                    'invoice_number' => $invoice->invoice_number,
                    'status' => Invoice::STATUS[$invoice->status->value],
                    'issued_at' => $invoice->issued_at,
                    'due_at' => $invoice->due_at,
                ])
            ,
        ]);
    }

    public function create(CaseFile $case_file)
    {
        return inertia('Lawyer/Invoice/Create', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'invoice_number' => Invoice::getNewInvoiceNumber(),
            'items' => $case_file->disbursementItems()->recorded()->get(['id', 'name', 'description', 'amount'])->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'amount' => $item->amount->formatTo('en_MY'),
                'amount_numeric' => $item->amount->getAmount()->toFloat(),
            ]),
        ]);
    }

    public function store(StoreInvoiceRequest $request, CaseFile $case_file)
    {
        try {
            $items = DisbursementItem::whereIn('id', $request->items_id)->get();

             //Check the status for each item equals recorded
            foreach($items as $item) {
                if  (
                        $item->status->value != DisbursementItemStatusEnum::Recorded->value
                        || $item->invoice_id != null
                    ) 
                {
                    return back()->with('errorMessage', $item->name . ' has been added to other invoice please remove it from the list.');
                }
            }

            DB::beginTransaction();

            $invoice = $case_file->invoices()->create($request->except('items_id'));

            $invoice->disbursementItems()->saveMany($items);

            $items->toQuery()->update(['status' => DisbursementItemStatusEnum::DraftedForInvoice]);

            DB::commit();

            return redirect()->route('lawyer.invoices.index', $case_file)->with('successMessage', 'Successfully created the new invoice.');
        } catch(\Exception $e) {
            DB::rollBack();

            return back()->with('errorMessage', 'Failed: ' . $e->getMessage());
        }
    }
}
