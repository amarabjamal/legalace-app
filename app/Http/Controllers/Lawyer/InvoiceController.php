<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\DisbursementItemStatusEnum;
use App\Enums\InvoiceStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Jobs\CalculateInvoiceTotal;
use App\Mail\SendInvoice;
use App\Models\CaseFile;
use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\CaseFile\Invoices\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Spatie\Browsershot\Browsershot;

class InvoiceController extends Controller
{
    public function index(CaseFile $case_file)
    {
        return inertia('Lawyer/Invoice/Index', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'filters' => Request::all('search'),
            'invoices' => $case_file->invoices()
                ->filter(Request::only('search'))
                ->paginate(25)
                ->withQueryString()
                ->through(fn ($invoice) => [
                    'id' => $invoice->id,
                    'created_at' => $invoice->formatted_created_at,
                    'invoice_number' => $invoice->invoice_number,
                    'status' => Invoice::STATUS[$invoice->status->value],
                    'status_value' => $invoice->status->value,
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
            'invoice' => [
                'company' => auth()->user()->company->only('name', 'address'),
                'client' => $case_file->client->only('name', 'address'),
                'number' => Invoice::getNextInvoiceNumber(),
            ],
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
            dispatch_sync(new CalculateInvoiceTotal($invoice));
            $this->show($case_file, $invoice);
        }

        return inertia('Lawyer/Invoice/Show', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'invoice' => [
                'id' => $invoice->id,
                'company' => $invoice->company->only('name', 'address'),
                'client' => $invoice->caseFile->client->only('name', 'address'),
                'number' => $invoice->invoice_number,
                'invoice_date' => $invoice->formatted_invoice_date,
                'due_date' => $invoice->formatted_due_date,
                'subtotal' => $invoice->subtotal->formatTo('en-MY'),
                'tax' => $invoice->tax_amount->formatTo('en-MY'),
                'total' => $invoice->grand_total->formatTo('en-MY'),
                'notes' => $invoice->notes,
                'status_value' => $invoice->status->value,
                'status_label' => Invoice::STATUS[$invoice->status->value],
                'sent_at' => $invoice->formatted_sent_at,
                'payment' => isset($invoice->payment) ? [
                    'date' => $invoice->payment->formatted_date,
                    'method' => InvoicePayment::PAYMENT_METHODS[$invoice->payment->payment_method_code->value],
                    'amount' => $invoice->payment->amount->formatTo('en-MY'),
                    'created_by' => $invoice->payment->createdBy->only('name'),
                ] : null,
                'has_receipt' => isset($invoice->receipt),
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

    public function edit(CaseFile $case_file, Invoice $invoice)
    {
        if(!$invoice->isEditable()) 
        {
            return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('warningMessage', 'This invoice cannot be edited since it is no longer a draft.');
        }

        return inertia('Lawyer/Invoice/Edit', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'invoice' => [
                'id' => $invoice->id,
                'company' => $invoice->company->only('name', 'address'),
                'client' => $invoice->caseFile->client->only('name', 'address'),
                'number' => $invoice->invoice_number,
                'invoice_date' => $invoice->issued_at->format('Y-m-d'),
                'due_date' => $invoice->due_at->format('Y-m-d'),
                'notes' => $invoice->notes,
                'items_id' => $invoice->disbursementItems->pluck('id'),
            ],
            'items' => $case_file->disbursementItems()->recorded()->orWhere('invoice_id', $invoice->id)->get(['id', 'name', 'description', 'amount'])->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'amount' => $item->amount->formatTo('en_MY'),
                'amount_numeric' => $item->amount->getAmount()->toFloat(),
            ]),
        ]);
    }

    public function update(UpdateInvoiceRequest $request, CaseFile $case_file, Invoice $invoice)
    {
        if(!$invoice->isEditable()) 
        {
            return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('warningMessage', 'This invoice cannot be edited since it is no longer a draft.');
        }

        try {
            $items = DisbursementItem::whereIn('id', $request->items_id)->get();

             //Check the status for each item equals recorded
            foreach($items as $item) {
                if  (
                        ($item->status->value != DisbursementItemStatusEnum::Recorded->value
                        || $item->invoice_id != null) && $item->invoice_id != $invoice->id
                    ) 
                {
                    return back()->with('errorMessage', $item->name . ' has been added to other invoice please remove it from the list.');
                }
            }

            DB::beginTransaction();

            $invoice->update($request->except('items_id'));

            $removedItems = $invoice->disbursementItems()->whereNotIn('id', $request->items_id)->get();
            if($removedItems->isNotEmpty())
            {
                $removedItems->toQuery()->update(['status' => DisbursementItemStatusEnum::Recorded]);
                foreach($removedItems as $removedItem) {
                    $removedItem->invoice()->dissociate();
                    $removedItem->save();
                }
            }

            $invoice->disbursementItems()->saveMany($items);

            $items->toQuery()->update(['status' => DisbursementItemStatusEnum::DraftedForInvoice]);
            
            DB::commit();
            
            CalculateInvoiceTotal::dispatchSync($invoice);
            
            return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('successMessage', 'Successfully updated the invoice.');
        } catch(\Exception $e) {
            DB::rollBack();

            return back()->with('errorMessage', 'Failed: ' . $e->getMessage());
        }
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

            return back()->with('warningMessage', 'You are not allowed to delete this record since its status is not draft.');
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Error: failed to delete.' . $e->getMessage());
        }
    }

    public function setOpen(CaseFile $case_file, Invoice $invoice) 
    {
        if($invoice->status != InvoiceStatusEnum::Draft) {
            return back()->with('errorMessage', 'The invoice status is no longer a draft.');
        }

        DB::transaction(function() use ($invoice) {
            $invoice->update(['status' => InvoiceStatusEnum::Open]);
        });

        return back()->with('successMessage', 'The invoice status is set to open.');
    }

    public function emailInvoice(CaseFile $case_file, Invoice $invoice) 
    {
        if($invoice->status != InvoiceStatusEnum::Draft && $invoice->status != InvoiceStatusEnum::Open && $invoice->status != InvoiceStatusEnum::Sent) {
            return back()->with('errorMessage', 'Invalid action.');
        }

        try { 
            DB::transaction(function() use ($case_file, $invoice) {
                $invoice->update(['status' => InvoiceStatusEnum::Sent]);
                $invoice->disbursementItems()->update(['status' => DisbursementItemStatusEnum::Invoiced]);       

                $pdf = $this->generatePdf($case_file, $invoice);

                Mail::to($case_file->client->email)
                    ->send(new SendInvoice($invoice, $pdf, $case_file->client->name));
            });
        } catch (\Exception $e) {
            dd($e);
            return back()->with('errorMessage', 'Failed to send the receipt');
        }

        return back()->with('successMessage', 'The invoice is emailed to the client.');
    }

    public function markSent(CaseFile $case_file, Invoice $invoice)
    {
        try {
            DB::transaction(function() use ($invoice) {
                $invoice->sent_at = now();
                $invoice->save();
            });
        } catch (\Exception $e)
        {
            return back()->with('errorMessage', 'Failed to mark this invoice as sent.');
        }

        return back()->with('successMessage', 'This invoice is marked as sent.');
    }

    public function downloadPdf(CaseFile $case_file, Invoice $invoice)
    {
        $pdf = $this->generatePdf($case_file, $invoice);

        return response()->stream(function () use ($pdf) {
            echo $pdf;
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename=invoice.pdf',
        ]);
    }

    private function generatePdf(CaseFile $case_file, Invoice $invoice)
    {
        $data = [
            'case_file' => [ 
                'number' => $case_file->file_number,
                'client' => $case_file->client->only('name', 'address'),

            ],
            'invoice' => [
                'company' => $invoice->company->only('name', 'address'),
                'number' => $invoice->invoice_number,
                'invoice_date' => $invoice->formatted_invoice_date,
                'due_date' => $invoice->formatted_due_date,
                'subtotal' => $invoice->subtotal->formatTo('en-MY'),
                'tax' => $invoice->tax_amount->formatTo('en-MY'),
                'total' => $invoice->grand_total->formatTo('en-MY'),
                'notes' => $invoice->notes,
            ],
            'items' => $invoice->disbursementItems->map(fn($item) => 
                [
                    'name' => $item->name,
                    'description' => $item->description,
                    'amount' => $item->amount->formatTo('en-MY'),
                ]
            ),
        ];

        $html = view('templates.invoice', $data)->render();

        return Browsershot::html($html)
                ->margins(18, 18, 18, 18)
                ->format('A4')
                ->showBackground()
                ->pdf();
    }
}
