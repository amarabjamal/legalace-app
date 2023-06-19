<?php

namespace App\Jobs;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CalculateQuotationTotal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Quotation $quotation)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            $this->quotation->subtotal = $this->quotation->calculateSubtotal();
            $this->quotation->tax_rate = $this->quotation->calculateTaxRate();
            $this->quotation->tax_amount = $this->quotation->calculateTax();
            $this->quotation->total = $this->quotation->calculateTotal();

            $this->quotation->save();
        });
    }
}
