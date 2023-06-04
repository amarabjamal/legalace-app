<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\DisbursementItemStatusEnum;
use App\Enums\InvoiceStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Exceptions\FileNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoicePaymentRequest;
use App\Models\BankAccount;
use App\Models\BankAccountType;
use App\Models\CaseFile;
use App\Models\CaseFile\Invoices\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelOptions\Options;

class InvoicePaymentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CaseFile $case_file, Invoice $invoice)
    {
        return inertia('Lawyer/InvoicePayment/Create', [
            'case_file' => [ 
                'id' => $case_file->id,
                'file_number' => $case_file->file_number,
            ],
            'invoice' => [
                'id' => $invoice->id,
                'number' => $invoice->invoice_number,
            ],
            'payment_methods' => Options::forEnum(PaymentMethodEnum::class),
            'client_bank_accounts' => BankAccount::clientAccount()->get(['id', 'label']),
            'suggested_description' => 'Payment for Invoice No. ' . $invoice->invoice_number,
        ]);   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoicePaymentRequest $request, CaseFile $case_file, Invoice $invoice)
    {
        $filePath = null;

        try {
            if(!$request->hasFile('receipt')) {
                throw new FileNotFoundException('File not found.');
            } else {
                $fileName = uniqid('INV_RECEIPT_') . '_' . date('Ymd') . '_' .time() . '.' . $request->file('receipt')->extension();
                $filePath = $request->file('receipt')->storeAs(InvoicePayment::RECEIPT_PATH, $fileName);

                $request->merge(['receipt_filename' => $fileName]);
            }

            DB::transaction(function() use ($invoice, $request) {
                $invoice->payment()->create($request->all());
                $invoice->update(['status' => InvoiceStatusEnum::Paid]);
                $invoice->disbursementItems()->update(['status' => DisbursementItemStatusEnum::PaidByClient]);
            });
        } catch(\Exception $e) {
            if(Storage::exists($filePath))
            {
                Storage::delete($filePath);
            }

            return back()->with('errorMessage', 'Failed to update invoice payment.' . $e->getMessage());  
        }

        return redirect()->route('lawyer.invoices.show', ['case_file' => $case_file, 'invoice' => $invoice])->with('successMessage', 'The invoice payment is saved.');
    }

    public function downloadReceipt(CaseFile $case_file, Invoice $invoice)
    {
        if(!isset($invoice->payment->receipt_filename)) {
            return abort(404);
        }
        
        $pathToFile = storage_path('app/' . InvoicePayment::RECEIPT_PATH . $invoice->payment->receipt_filename);
        
        return response()->file($pathToFile);
    }
}
