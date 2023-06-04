<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReceiptRequest;
use App\Models\CaseFile;
use App\Models\CaseFile\Invoices\Invoice;
use App\Models\InvoicePayment;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function create(CaseFile $case_file, Invoice $invoice) 
    {
        if(!isset($invoice->payment)) 
        {
            return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('errorMessage', 'Please record the payment for this invoice first.');  
        } else if(isset($invoice->payment->receipt))
        {
            return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('warningMessage', 'Receipt is already created.');  
        }

        return inertia('Lawyer/Receipt/Create', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'invoice' => [
                'id' => $invoice->id,
                'company' => $invoice->company->only('name', 'address'),
                'client' => $invoice->caseFile->client->only('name', 'address'),
                'number' => $invoice->invoice_number,
                'subtotal' => $invoice->subtotal->formatTo('en-MY'),
                'tax' => $invoice->tax_amount->formatTo('en-MY'),
                'total' => $invoice->grand_total->formatTo('en-MY'),
                'payment' => [
                    'method' => InvoicePayment::PAYMENT_METHODS[$invoice->payment->payment_method_code->value],
                    'date' => $invoice->payment->formatted_date,
                ],
            ],
            'items' => $invoice->disbursementItems->map(fn($item) => 
                [
                    'name' => $item->name,
                    'description' => $item->description,
                    'amount' => $item->amount->formatTo('en-MY'),
                ]
            ),
            'receipt' => [
                'number' => Receipt::getNextReceiptNumber(),
                'date' => now()->format('Y/m/d'),
            ],
        ]);
    }

    public function store(StoreReceiptRequest $request, CaseFile $case_file, Invoice $invoice)
    {
        if(!isset($invoice->payment)) 
        {
            return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('errorMessage', 'Please record the payment for this invoice first.');  
        } else if(isset($invoice->payment->receipt))
        {
            return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('warningMessage', 'Receipt is already created.');  
        }

        $receiptInputs = $request->all();

        try {
            DB::transaction(function() use ($receiptInputs, $invoice) {
                $invoice->payment->receipt()->create($receiptInputs);
            });
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to create receipt. ' . $e->getMessage());  
        }

        return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('successMessage', 'The receipt is created.');
    }

    public function show(CaseFile $case_file, Invoice $invoice)
    {
        $receipt = $invoice->payment->receipt;

        return inertia('Lawyer/Receipt/Show', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'invoice' => [
                'id' => $invoice->id,
                'company' => $invoice->company->only('name', 'address'),
                'client' => $invoice->caseFile->client->only('name', 'address'),
                'number' => $invoice->invoice_number,
                'subtotal' => $invoice->subtotal->formatTo('en-MY'),
                'tax' => $invoice->tax_amount->formatTo('en-MY'),
                'total' => $invoice->grand_total->formatTo('en-MY'),
                'payment' => [
                    'method' => InvoicePayment::PAYMENT_METHODS[$invoice->payment->payment_method_code->value],
                    'date' => $invoice->payment->formatted_date,
                ],
            ],
            'items' => $invoice->disbursementItems->map(fn($item) => 
                [
                    'name' => $item->name,
                    'description' => $item->description,
                    'amount' => $item->amount->formatTo('en-MY'),
                ]
            ),
            'receipt' => [
                'number' => $receipt->receipt_number,
                'date' => $receipt->created_at->format('Y/m/d'),
                'notes' => $receipt->notes,
            ],
        ]);
    }
}
