<?php

namespace App\Enums;

enum PaymentMethodEnum: int
{
    case InstantTransfer = 1;
    case IBGTransfer = 2;
    case Cheque = 3;


    public static function labels(): array
    {
        return [
            1 => 'Bank Transfer',
            2 => 'Cash',
            3 => 'Cheque',
            4 => 'Credit Card',
        ];
    }
}
