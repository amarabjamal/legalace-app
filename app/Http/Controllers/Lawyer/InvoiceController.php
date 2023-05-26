<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\DisbursementItemStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Jobs\CalculateInvoiceTotal;
use App\Models\CaseFile;
use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\CaseFile\Invoices\Invoice;
use Carbon\Carbon;
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
                    'id' => $invoice->id,
                    'created_at' => $invoice->formatted_created_at,
                    'invoice_number' => $invoice->invoice_number,
                    'status' => Invoice::STATUS[$invoice->status->value],
                    'issued_at' => $invoice->formatted_invoice_date,
                    'due_at' => $invoice->formatted_due_date,
                    'total' =>  $invoice->grand_total != null ? $invoice->grand_total->formatTo('en_MY') : null,
                    'created_by' => $invoice->createdBy ? $invoice->createdBy->only('name') : null,
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
            
            CalculateInvoiceTotal::dispatchSync($invoice);
            
            return redirect()->route('lawyer.invoices.index', $case_file)->with('successMessage', 'Successfully created the new invoice.');
        } catch(\Exception $e) {
            DB::rollBack();

            return back()->with('errorMessage', 'Failed: ' . $e->getMessage());
        }
    }

    public function show(CaseFile $case_file, Invoice $invoice)
    {
        if($invoice->subtotal == null) {
            CalculateInvoiceTotal::dispatchSync($invoice);
        }

        return inertia('Lawyer/Invoice/Show', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'invoice' => [
                'company' => $invoice->company->only('name', 'address'),
                'client' => $invoice->caseFile->client->only('name', 'address'),
                'number' => $invoice->invoice_number,
                'invoice_date' => $invoice->formatted_invoice_date,
                'due_date' => $invoice->formatted_due_date,
                'subtotal' => $invoice->subtotal->formatTo('en-MY'),
                'tax' => $invoice->tax_amount->formatTo('en-MY'),
                'total' => $invoice->grand_total->formatTo('en-MY'),
                'notes' => $invoice->notes,
                'is' => [
                    'editable' => $invoice->isEditable(),
                ],
            ],
            'items' => $invoice->disbursementItems->map(fn($item) => 
                [
                    'name' => $item->name,
                    'description' => $item->description,
                    'amount' => $item->amount->formatTo('en-MY'),
                ]
            ),
        ]);
    }

    public function destroy(CaseFile $case_file, Invoice $invoice)
    {
        try {
            if($invoice->isDeletable()) 
            {
                DB::transaction(function() use($invoice) {
                    $invoice->disbursementItems()->update(['status' => DisbursementItemStatusEnum::Recorded]);
                    foreach($invoice->disbursementItems as $item) {
                        $item->invoice()->dissociate();
                        $item->save();
                    }
                    
                    $invoice->delete();
                });

                return redirect()->route('lawyer.invoices.index', $case_file)->with('successMessage', 'Successfully deleted the record.');
            }

            return back()->with('infoMessage', 'You are not allowed to delete this record since its status is not draft.');
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Error: failed to delete.' . $e->getMessage());
        }
    }
}
