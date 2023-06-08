<?php
  
namespace App\Enums;
 
enum InvoiceStatusEnum:int {
    case Draft = 1;
    case Open = 2;
    case Sent = 3;
    case Paid = 4;
    case Overdue = 5;
    case Void = 6;
    case Uncollectible = 7;
}