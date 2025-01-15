<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\User;
use Inertia\Inertia;
use App\Models\FirmAccount;
use App\Models\BankAccounts;
use App\Models\CaseFiles;
use App\Models\ClaimVoucher;
use App\Models\Client;
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
            'total_clients' => Client::all()->count(),
            'total_vouchers' => ClaimVoucher::all()->count(),
            'total_accounts' => BankAccount::all()->count(),
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
            ->paginate(4) // Paginate the caseFile data
            ->withQueryString() // Preserve the query string
            ->through(fn($caseFile) => [
                'id' => $caseFile->id,
                'matter' => $caseFile->matter,
            ]);


        $firmAccounts = BankAccounts::query()
            ->rightJoin('firm_account as b', 'bank_accounts.id', '=', 'b.bank_account_id')
            ->select(
                'label',
                DB::raw('(IFNULL(SUM(debit), 0) - IFNULL(SUM(credit), 0)) + IFNULL(opening_balance, 0) AS opening_balance'),
                DB::raw('IFNULL(SUM(debit), 0) AS total_debit'),
                DB::raw('IFNULL(SUM(credit), 0) AS total_credit')
            )
            ->groupBy('label', 'opening_balance');

        $clientAccounts = BankAccounts::query()
            ->rightJoin('client_accounts as b', 'bank_accounts.id', '=', 'b.bank_account_id')
            ->select(
                'label',
                DB::raw('(IFNULL(SUM(debit), 0) - IFNULL(SUM(credit), 0)) + IFNULL(opening_balance, 0) AS opening_balance'),
                DB::raw('IFNULL(SUM(debit), 0) AS total_debit'),
                DB::raw('IFNULL(SUM(credit), 0) AS total_credit')
            )
            ->groupBy('label', 'opening_balance');

        // Combine both queries with unionAll
        $bankAccount = $firmAccounts->unionAll($clientAccounts)->get();


        //for pagination
        $bankAccount = DB::table(DB::raw("(
                SELECT 
                    label,
                    opening_balance,
                    (IFNULL(SUM(debit), 0) - IFNULL(SUM(credit), 0)) + IFNULL(opening_balance, 0) AS balance,
                    IFNULL(SUM(debit), 0) AS total_debit,
                    IFNULL(SUM(credit), 0) AS total_credit
                FROM 
                    (
                        SELECT 
                            'firm_account' AS type,
                            b.bank_account_id,
                            b.debit,
                            b.credit,
                            ba.label,
                            ba.opening_balance
                        FROM 
                            firm_account b
                        INNER JOIN 
                            bank_accounts ba ON b.bank_account_id = ba.id
            
                        UNION ALL
            
                        SELECT 
                            'client_account' AS type,
                            b.bank_account_id,
                            b.debit,
                            b.credit,
                            ba.label,
                            ba.opening_balance
                        FROM 
                            client_accounts b
                        INNER JOIN 
                            bank_accounts ba ON b.bank_account_id = ba.id
                    ) AS subquery
                GROUP BY 
                    label, opening_balance
            ) AS subquery"))
            ->paginate(4)
            ->withQueryString();

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
