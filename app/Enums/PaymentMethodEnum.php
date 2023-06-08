<?php
  
namespace App\Enums;
 
enum PaymentMethodEnum:int {
    case InstantTransfer = 1;
    case IBGTransfer = 2;
    case Cheque = 3;


    public static function labels() : array 
    {
        return [
            1 => 'Instant Transfer',
            2 => 'Interbank GIRO Transfer',
            3 => 'Cheque',
        ];
    }
}