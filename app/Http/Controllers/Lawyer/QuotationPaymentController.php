<?php

namespace App\Http\Controllers\Lawyer;

use App\Exceptions\FileNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotationPaymentRequest;
use App\Models\CaseFile;
use App\Models\QuotationPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuotationPaymentController extends Controller
{
    public function __invoke(StoreQuotationPaymentRequest $request, CaseFile $case_file)
    {
        $quotation = $case_file->quotation;

        if($quotation->isPaid()) {
            return redirect()->route('lawyer.quotation.show', $case_file)->with('warningMessage', 'This quotation is already paid.');
        }

        $filePath = null;

        if($quotation->isPaid()) {
            return back()->with('warningMessage', 'This quotation has been paid.');
        }

        try {
            if(!$request->hasFile('receipt')) {
                throw new FileNotFoundException('File not found.');
            } else {
                DB::transaction(function() use ($quotation, &$filePath, $request) {
                    $fileName = uniqid('QTE_RECEIPT_') . '_' . date('Ymd') . '_' .time() . '.' . $request->file('receipt')->extension();
                    $filePath = $request->file('receipt')->storeAs(QuotationPayment::RECEIPT_PATH, $fileName);
    
                    $request->merge(['receipt_filename' => $fileName]);

                    $quotation->payment()->create($request->all());
                });
            }
        } catch (\Exception $e) {
            if(Storage::exists($filePath))
            {
                Storage::delete($filePath);
            }
            dd($e);
            return back()->with('errorMessage', 'Failed to update invoice payment.');  
        }

        return back()->with('successMessage', 'Payment has been saved.');
    }
}
