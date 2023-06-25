<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisbursementItemTypeRequest;
use App\Models\CaseFile\DisbursementItem\DisbursementItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisbursementItemTypeController extends Controller
{
    public function __invoke(StoreDisbursementItemTypeRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                DisbursementItemType::create($request->all());
            });
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to add the record type.');
        }

        return back()->with('successMessage', 'New Record Type is added.');
    }
}
