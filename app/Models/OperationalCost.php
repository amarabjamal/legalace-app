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
        'company_id',
        'bank_account_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function recordAmount(){
        
    }
    public function getUpcomingRecurring(){

    }
    public function getCost(string $startDate, string $endDate){

    }
}
