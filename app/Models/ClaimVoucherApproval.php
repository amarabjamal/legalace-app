<?php

namespace App\Models;

use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimVoucherApproval extends Model
{
    use HasFactory, HasCompanyScope, SoftDeletes;

    protected $table = 'claim_voucher_approvals';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'claim_voucher_id',
        'bank_account_id',
        'reimbursed_date',
        'notes',
    ];
}
