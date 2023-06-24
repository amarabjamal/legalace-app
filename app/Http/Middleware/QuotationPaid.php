<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class QuotationPaid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $caseFile = $request->route('case_file');
        if(!$caseFile->hasQuotation()) {
            return back()->with('warningMessage', 'This case file does not have a quotation yet. Create one first.');
        }

        $quotation = $caseFile->quotation;

        // need refactor
        if($quotation && (!$quotation->is_paid || $quotation->payment_date === null) ) {
            return back()->with('warningMessage', 'The client must pay the deposit first to proceed.');
        }

        return $next($request);
    }
}
