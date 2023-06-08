<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class FileNotFoundException extends Exception
{
    public function report()
    {
        Log::debug('File not found.');
    }
}
