<?php
  
namespace App\Enums;
 
enum DisbursementItemStatusEnum:int {
    case Recorded = 1;
    case DraftedForInvoice = 2;
    case Invoiced = 3;
    case PaidByClient = 4;
    case DraftedForClaim = 5;
    case PendingClaimApproval = 6;
    case ApprovedForClaim = 7;
    case Disbursed = 8;
    case Denied = 9;
}