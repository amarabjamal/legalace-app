<?php
  
namespace App\Enums;
 
enum DisbursementItemFundTypeEnum:int {
    case PaidByLawyer = 1;
    case FirmAccount = 2;
    case PettyCash = 3;
    case PendingClaimApproval = 4;
    case ApprovedForClaim = 5;
    case Disbursed = 6;
}