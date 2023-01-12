<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManageCaseFile extends Controller
{
    public function index()
    {
        return Inertia::render('Lawyer/CaseFile/Index', [
        ]);
    }
}
