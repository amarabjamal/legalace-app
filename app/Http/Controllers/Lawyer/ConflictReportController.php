<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConflictReportRequest;
use App\Models\CaseFile;
use App\Notifications\ConflictReportSentNotification;
use Illuminate\Support\Facades\DB;

class ConflictReportController extends Controller
{
    public function index(CaseFile $case_file)
    {
        return $case_file->conflictReports()
                ->latest()
                ->paginate(1)
                ->through(fn($report) => [
                    'creator' => $report->createdBy->name,
                    'content' => $report->content,
                    'posted_on' => $report->relative_created_time,
                ]);
    }

    public function store(StoreConflictReportRequest $request, CaseFile $case_file)
    {
        if($case_file->no_conflict_checked) {
            return back()->with('errorMessage', 'This case file has been resolved as no conflict. It no longer accepting conflict report.');
        }

        try {
            DB::transaction(function () use ($request, $case_file) {
                $case_file->conflictReports()->create($request->all());

                $case_file->createdBy->notify(new ConflictReportSentNotification($case_file, auth()->user()));
            });
        } catch (\Exception $e) {
            dd($e);
        }

        return back()->with('successMessage', 'Report has beend sumitted.');
    }
}
