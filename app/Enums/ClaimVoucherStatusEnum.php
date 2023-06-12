<?php
  
namespace App\Enums;
 
enum ClaimVoucherStatusEnum:int {
    case Draft = 1;
    case Submitted = 2;
    case Approved = 3;
    case Rejected = 4;
    case Reimbursed = 5;
}