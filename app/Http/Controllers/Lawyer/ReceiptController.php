<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReceiptRequest;
use App\Mail\SendReceipt;
use App\Models\CaseFile;
use App\Models\CaseFile\Invoices\Invoice;
use App\Models\InvoicePayment;
use App\Models\Receipt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Browsershot\Browsershot;

class ReceiptController extends Controller
{
    public function create(CaseFile $case_file, Invoice $invoice) 
    {
        if(!isset($invoice->payment)) 
        {
            return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('errorMessage', 'Please record the payment for this invoice first.');  
        } else if(isset($invoice->receipt))
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
        } else if(isset($invoice->receipt))
        {
            return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('warningMessage', 'Receipt is already created.');  
        }

        $receiptInputs = $request->all();

        try {
            DB::transaction(function() use ($receiptInputs, $invoice) {
                $invoice->receipt()->create($receiptInputs);
            });
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to create receipt. ' . $e->getMessage());  
        }

        return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('successMessage', 'The receipt is created.');
    }

    public function show(CaseFile $case_file, Invoice $invoice)
    {
        if (!isset($invoice->receipt)) 
        {
            return back()->with('errorMessage', 'The receipt does not exist.');
        }

        $receipt = $invoice->receipt;

        return inertia('Lawyer/Receipt/Show', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'invoice' => [
                'id' => $invoice->id,
                'company' => $invoice->company->only('name', 'address'),
                'client' => $case_file->client->only('name', 'address'),
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
                'is_sent' => $receipt->is_sent,
            ],
        ]);
    }

    public function emailReceipt(CaseFile $case_file, Invoice $invoice) 
    {
        if (!isset($invoice->receipt)) 
        {
            return back()->with('errorMessage', 'The receipt does not exist.');
        }

        try
        {
            $pdf = $this->generatePdf($case_file, $invoice);

            Mail::to($case_file->client->email)
                ->send(new SendReceipt($invoice->receipt, $pdf, $case_file->client->name));

            DB::transaction(function() use ($invoice) {
                $invoice->receipt()->update(['is_sent' => true]);
            });
        }
        catch(\Exception $e)
        {
            return back()->with('errorMessage', 'Failed to send the receipt');
        }

        return back()->with('successMessage', "Email has been sent to client's registered email.");
    }

    public function markSent(CaseFile $case_file, Invoice $invoice) 
    {
        if (!isset($invoice->receipt)) 
        {
            return back()->with('errorMessage', 'The receipt does not exist.');
        }

        try {
            DB::transaction(function() use ($invoice) {
                $invoice->receipt()->update(['is_sent' => true]);
            });
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to mark this receipt as sent.');
        }

        return back()->with('successMessage', 'The receipt is marked as sent.');
    }

    public function downloadPdf(CaseFile $case_file, Invoice $invoice)
    {
        $pdf = $this->generatePdf($case_file, $invoice);

        return response()->stream(function () use ($pdf) {
            echo $pdf;
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename=receipt.pdf',
        ]);
    }

    private function generatePdf(CaseFile $case_file, Invoice $invoice)
    {
        $data = [
            'case_file' => [ 
                'number' => $case_file->file_number,
            ],
            'invoice' => [
                'company' => $invoice->company->only('name', 'address'),
                'client' => $case_file->client->only('name', 'address'),
                'number' => $invoice->invoice_number,
                'subtotal' => $invoice->subtotal->formatTo('en-MY'),
                'tax' => $invoice->tax_amount->formatTo('en-MY'),
                'total' => $invoice->grand_total->formatTo('en-MY'),
            ],
            'items' => $invoice->disbursementItems->map(fn($item) => 
                [
                    'name' => $item->name,
                    'description' => $item->description,
                    'amount' => $item->amount->formatTo('en-MY'),
                ]
            ),
            'receipt' => [
                'number' => $invoice->receipt->receipt_number,
                'date' => $invoice->receipt->created_at->format('d/m/Y'),
                'notes' => $invoice->receipt->notes,
            ],
            'payment' => [
                'method' => InvoicePayment::PAYMENT_METHODS[$invoice->payment->payment_method_code->value],
                'date' => $invoice->payment->formatted_date,
            ],
        ];

        $content = view('templates.receipt', $data)->render();
        return Browsershot::html($content)
                ->margins(18, 18, 18, 18)
                ->format('A4')
                ->showBackground()
                ->pdf();
    }
}
