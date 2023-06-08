<?php
  
namespace App\Enums;
 
enum DisbursementItemFundTypeEnum:int {
    case PaidByLawyer = 1;
    case FirmAccount = 2;
    case PettyCash = 3;


    public static function labels() : array 
    {
        return [
            1 => 'Paid By Lawyer',
            2 => 'Firm Account',
            3 => 'Petty Cash',
        ];
    }
}