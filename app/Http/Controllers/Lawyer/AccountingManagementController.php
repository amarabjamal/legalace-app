<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\ClaimVoucherStatusEnum;
use App\Enums\DisbursementItemStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClaimVoucherRequest;
use App\Jobs\CalculateclaimVoucherTotal;
use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\ClaimVoucher;
use App\Models\User;
use App\Models\OperationalCost;
use App\Models\FirmAccount;
use App\Notifications\SubmitClaimVoucherNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class AccountingManagementController extends Controller
{
    public function index()
    {
        return inertia('Lawyer/AccountingManagement/Index', [
            'filters' => Request::all('search'),
            'claim_vouchers' => ClaimVoucher::where('requester_user_id', auth()->id())
                ->filter(Request::only('search'))
                ->paginate(25)
                ->withQueryString()
                ->through(fn($voucher) => [
                    'id' => $voucher->id,
                    'ticket_number' => $voucher->ticket_number,
                    'submission_date' => $voucher->submission_date ? $voucher->submission_date : 'N/A',
                    'amount' => $voucher->amount->formatTo('en_MY'),
                    'status' => ClaimVoucher::STATUS[$voucher->status->value],
                    'approver' => $voucher->approver->only('name'),
                ]),
        ]);
    }

    public function create()
    {

        return inertia('Lawyer/AccountingManagement/Create', [
            'ticket_number' => ClaimVoucher::getNextTicketNumber(),
            'approvers' => User::admin()->notCurrentUser()->get(['id', 'name']),
            'reimbursable_items' => DisbursementItem::paidByLawyer()
                ->fromInvoiceCreatedByCurrentUser()
                ->get(['id', 'name', 'description', 'amount'])
                ->map(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'amount_display' => $item->amount->formatTo('en_MY'),
                    'amount_numeric' => $item->amount->getAmount()->toFloat(),
                ]),
        ]);
    }

    public function store(StoreClaimVoucherRequest $request)
    {
        try {
            $items = DisbursementItem::whereIn('id', $request->item_id)->get();

            //Check the status for each item equals paid by client
            foreach ($items as $item) {
                if (
                    $item->status != DisbursementItemStatusEnum::PaidByClient
                    || $item->claim_voucher_id != null
                ) {
                    return back()->with('errorMessage', $item->name . ' is not eligible for claim.');
                }
            }

            DB::beginTransaction();

            $claimVoucher = ClaimVoucher::create($request->except('item_id'));

            $claimVoucher->items()->saveMany($items);

            $items->toQuery()->update(['status' => DisbursementItemStatusEnum::DraftedForClaim]);

            dispatch_sync(new CalculateclaimVoucherTotal($claimVoucher));

            DB::commit();

            return redirect()->route('lawyer.claim-vouchers.index')->with('successMessage', 'Successfully created the new claim voucher.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('errorMessage', 'Failed: ' . $e->getMessage());
        }
    }

    public function show(ClaimVoucher $claim_voucher)
    {

        return inertia('Lawyer/AccountingManagement/Show', [
            'claim_voucher' => $claim_voucher->toResource(),
            'items' => $claim_voucher->items->map(
                fn($item) =>
                [
                    'name' => $item->name,
                    'description' => $item->description,
                    'amount' => $item->amount->formatTo('en-MY'),
                ]
            ),
        ]);
    }

    public function submitClaimVoucher(ClaimVoucher $claim_voucher)
    {
        if ($claim_voucher->status !== ClaimVoucherStatusEnum::Draft) {
            return back()->with('errorMessage', 'The claim voucher status is no longer a draft.');
        }

        try {
            DB::transaction(function () use ($claim_voucher) {
                $claim_voucher->submission_date = today();
                $claim_voucher->status = ClaimVoucherStatusEnum::Submitted;
                $claim_voucher->save();

                $claim_voucher->items()->update(['status' => DisbursementItemStatusEnum::PendingClaimApproval]);

                $claim_voucher->approver->notify(new SubmitClaimVoucherNotification($claim_voucher, auth()->user()));
            });
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to submit the claim voucher.');
        }

        return back()->with('successMessage', 'The claim voucher has been submitted to ' . $claim_voucher->approver->name . '.');
    }

    public function profitAndLoss()
    {
        $totalOperatingIncome = FirmAccount::query()
            ->where('description', 'like', 'payment_received')
            ->where('transaction_type', 'like', 'funds in')
            ->sum('debit');

        $listGroupOperatingIncome = FirmAccount::query()
            ->rightJoin('bank_accounts as b', 'firm_account.bank_account_id', '=', 'b.bank_account_type_id')
            ->select(
                'b.label',
                'firm_account.bank_account_id',
                'firm_account.description',
                'firm_account.transaction_type',
                DB::raw('SUM(firm_account.debit) AS debit'),
                DB::raw('SUM(firm_account.credit) AS credit')
            )
            ->where('description', 'like', 'payment_received')
            ->where('firm_account.transaction_type', 'like', 'funds in')
            ->groupBy('firm_account.bank_account_id', 'b.label', 'firm_account.description', 'firm_account.transaction_type')
            ->get();

        $totalEmployeeSalary = FirmAccount::query()
            ->where('description', 'like', 'employee_salary')
            ->where('transaction_type', 'like', 'funds out')
            ->sum('credit');

        $ListOperatingExpense = OperationalCost::query()
            ->select(
                'details',
                DB::raw('SUM(amount) AS amount'),
            )
            ->groupby('details')
            ->get();

        $totalListOperatingExpense = $ListOperatingExpense->sum('amount');

        $totalOperatingExpense = $totalEmployeeSalary + $totalListOperatingExpense;

        $totalNonOperatingIncome = FirmAccount::query()
            ->where('description', 'like', 'investing')
            ->where('transaction_type', 'like', 'funds in')
            ->groupBy('bank_account_id', 'description', 'transaction_type')
            ->sum('debit');

        $totalNonOperatingExpense = FirmAccount::query()
            ->where('description', 'like', 'investing')
            ->where('transaction_type', 'like', 'funds out')
            ->groupBy('bank_account_id', 'description', 'transaction_type')
            ->sum('credit');

        $netProfit = ($totalOperatingIncome + $totalNonOperatingIncome) - ($totalOperatingExpense - $totalNonOperatingExpense);

        return  [
            'totalOperatingIncome' => $totalOperatingIncome,
            'listGroupOperatingIncome' => $listGroupOperatingIncome,
            'totalEmployeeSalary' => $totalEmployeeSalary,
            'listOperatingExpense' => $ListOperatingExpense,
            'totalOperatingExpense' => $totalOperatingExpense,
            'totalNonOperatingIncome' => $totalNonOperatingIncome,
            'totalNonOperatingExpense' => $totalNonOperatingExpense,
            'netProfit' => $netProfit,
        ];
    }

    public function balance_sheet()
    {

        $cashDebit = FirmAccount::query()
            ->where('payment_method', 'like', 'cash')
            ->sum('debit');
        $cashCredit = FirmAccount::query()
            ->where('payment_method', 'like', 'cash')
            ->sum('credit');

        $cash = $cashDebit - $cashCredit;

        $bankDebit = FirmAccount::query()
            ->where('payment_method', 'like', 'bank')
            ->sum('debit');

        $bankCredit = FirmAccount::query()
            ->where('payment_method', 'like', 'bank')
            ->sum('credit');

        $bank = $bankDebit - $bankCredit;

        $acc_receivable = FirmAccount::query()
            ->where('transaction_type', 'like', 'funds in')
            ->where('payment_method', 'like', 'credit_card')
            ->sum('debit');

        $acc_receivable = FirmAccount::query()
            ->where('transaction_type', 'like', 'funds in')
            ->where('payment_method', 'like', 'credit_card')
            ->sum('debit');

        $total_curr_asset = $cash + $bank + $acc_receivable;

        $acc_payable = FirmAccount::query()
            ->where('transaction_type', 'like', 'funds out')
            ->where('payment_method', 'like', 'credit_card')
            ->sum('credit');

        $acc_payable = FirmAccount::query()
            ->where('transaction_type', 'like', 'funds out')
            ->where('payment_method', 'like', 'credit_card')
            ->sum('credit');

        $equities = FirmAccount::query()
            ->where('description', 'like', 'financing')
            ->where('transaction_type', 'like', 'funds in')
            ->sum('debit');

        $assetAcquisition = FirmAccount::query()
            ->where('description', 'like', 'asset_acquisition')
            ->where('transaction_type', 'like', 'funds out')
            ->sum('credit');

        $total_liabities_and_equities = $acc_payable + $equities;

        $profit_and_loss = self::profitAndLoss();

        return Inertia::render(
            'Lawyer/AccountingManagement/Balance',
            [
                'cash' => $cash,
                'bank' => $bank,
                'acc_receivable' => $acc_receivable,
                'total_curr_asset' => $total_curr_asset,
                'acc_payable' => $acc_payable,
                'total_curr_liabilities' => $acc_payable,
                'equities' => $equities,
                'total_equities' => $equities,
                'total_liabities_and_equities' => $total_liabities_and_equities,
                'assetAcquisition' => $assetAcquisition,
                'profit_and_loss' => $profit_and_loss,
            ]
        );
    }

    public function cash_flow()
    {
        ///
        $InvestingCash = FirmAccount::query()
            ->where('description', 'like', 'investing')
            ->where('payment_method', 'like', 'cash')
            ->sum('debit');

        $InvestingBank = FirmAccount::query()
            ->where('description', 'like', 'investing')
            ->where('payment_method', 'like', 'bank_transfer')
            ->sum('debit');

        $assetAcquisition = FirmAccount::query()
            ->where('description', 'like', 'asset_acquisition')
            ->sum('credit');

        $InvestingTotal = $InvestingCash + $InvestingBank - $assetAcquisition;

        ///
        $operatingCash = FirmAccount::query()
            ->where('description', '!=', 'investing')
            ->where('description', '!=', 'financing')
            ->where('payment_method', 'like', 'cash')
            ->sum('credit');

        $operatingBank = FirmAccount::query()
            ->where('description', '!=', 'investing')
            ->where('description', '!=', 'financing')
            ->where('payment_method', 'like', 'bank_transfer')
            ->sum('credit');

        $operatingTotal = $operatingCash + $operatingBank;

        ///
        $financingCash = FirmAccount::query()
            ->where('description', 'like', 'financing')
            ->where('payment_method', 'like', 'cash')
            ->sum('debit');

        $financingBank = FirmAccount::query()
            ->where('description', 'like', 'financing')
            ->where('payment_method', 'like', 'bank_transfer')
            ->sum('debit');

        $financingTotal = $financingCash + $financingBank;

        $endingCashBalance = $InvestingTotal + $operatingTotal + $financingTotal;



        return Inertia::render(
            'Lawyer/AccountingManagement/Cash',
            [
                'InvestingCash' => $InvestingCash,
                'InvestingBank' => $InvestingBank,
                'assetAcquisition' => $assetAcquisition,
                'InvestingTotal' => $InvestingTotal,
                'operatingCash' => $operatingCash,
                'operatingBank' => $operatingBank,
                'operatingTotal' => $operatingTotal,
                'financingCash' => $financingCash,
                'financingBank' => $financingBank,
                'financingTotal' => $financingTotal,
                'endingCashBalance' => $endingCashBalance
            ]
        );
    }


    public function profit_and_loss()
    {
        return Inertia::render(
            'Lawyer/AccountingManagement/Profit',
            self::profitAndLoss()
        );
    }
}
