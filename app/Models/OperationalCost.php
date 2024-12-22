<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalCost extends Model
{
    use HasFactory;
    protected $fillable = [
        'details',
        'amount',
        'is_recurring',
        'recurring_period',
        'is_paid',
        'company_id', //
        'bank_account_id',
        'upload', //
        'first_payment_date', //
        'no_of_payment', //
        'document_number', //
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public const UPLOAD_PATH = 'files/case-files/document-upload/operational-cost/';

    public function recordAmount() {}
    public function getUpcomingRecurring() {}
    public function getCost(string $startDate, string $endDate) {}
}
