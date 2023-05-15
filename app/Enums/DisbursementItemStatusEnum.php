<?php
  
namespace App\Enums;
 
enum DisbursementItemStatusEnum:int {
    case Recorded = 1;
    case Invoiced = 2;
    case RequestedForClaim = 3;
    case PendingClaimApproval = 4;
    case ApprovedForClaim = 5;
    case Disbursed = 6;
}