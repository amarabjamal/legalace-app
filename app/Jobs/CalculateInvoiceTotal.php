<?php

namespace App\Jobs;

use App\Models\CaseFile\Invoices\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CalculateInvoiceTotal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Invoice $invoice,
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            $this->invoice->subtotal = $this->invoice->calculateSubtotal();
            $this->invoice->tax_rate = $this->invoice->calculateTaxRate();
            $this->invoice->tax_amount = $this->invoice->calculateTax();
            $this->invoice->grand_total = $this->invoice->calculateTotal();
    
            $this->invoice->save();
        });
    }
}
