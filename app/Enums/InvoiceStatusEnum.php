<?php
  
namespace App\Enums;
 
enum InvoiceStatusEnum:int {
    case Draft = 1;
    case Open = 2;
    case Paid = 3;
    case Overdue = 4;
    case Void = 5;
    case Uncollectible = 6;
}