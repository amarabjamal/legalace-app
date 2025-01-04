<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use App\Models\FirmAccount;
use App\Models\BankAccounts;
use App\Models\CaseFiles;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function redirectToDashboard()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->indexAdmin();
        } else if ($user->hasRole('lawyer')) {
            return $this->indexLawyer();
        }
    }

    public function indexAdmin()
    {
        return Inertia::render('Admin/Dashboard', [
            'total_users' => User::all()->count(),
        ]);
    }

    public function indexLawyer()
    {
        $firmAccountList = FirmAccount::all();
        $date = [];
        $expenseData = [];
        $incomeDate = [];
        $incomeData = [];

        for ($i = 0; $i < count($firmAccountList); $i++) {
            if ($firmAccountList[$i]["transaction_type"] == "funds out") {
                $date[] = $firmAccountList[$i]['date'];
                $expenseData[] = $firmAccountList[$i]['credit'];
            } else {
                $date[] = $firmAccountList[$i]['date'];
                $incomeData[] = $firmAccountList[$i]['debit'];
            }
        }

        $topExpense = FirmAccount::query()
            ->select(
                'description',
                DB::raw('SUM(credit) AS total')
            )
            ->where('transaction_type', 'funds out')
            ->groupBy('firm_account.description')
            ->orderByDesc('total') // Optional: Sort by highest total_debit
            ->get();

        $caseFile = CaseFiles::query()
            ->get();


        $bankAccount = BankAccounts::get();

        return Inertia::render('Lawyer/Dashboard', [
            'date' => $date,
            'expenseData' => $expenseData,
            'incomeDate' => $incomeDate,
            'incomeData' => $incomeData,
            'bankAccount' => $bankAccount,
            'topExpense' => $topExpense,
            'caseFile' => $caseFile,
        ]);
    }
}
