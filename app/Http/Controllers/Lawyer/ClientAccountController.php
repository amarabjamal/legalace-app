<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClientAccount;
use App\Models\ClientAccountList;
use App\Models\BankAccounts;
use App\Models\FirmAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;

class ClientAccountController extends Controller
{
    const DB_NAME = "client_accounts";
    public function index(Request $request)
    {
        $accList = DB::table(self::DB_NAME);

        $clientAccountList = BankAccounts::query()
            ->rightJoin("client_accounts as b", 'bank_account_type_id', '=', 'b.bank_account_id')
            ->select(
                'bank_accounts.id',
                'label',
                DB::raw('(IFNULL(SUM(debit), 0) - IFNULL(SUM(credit), 0)) + IFNULL(opening_balance, 0) AS opening_balance'),
                DB::raw('IFNULL(SUM(debit), 0) AS total_debit'),
                DB::raw('IFNULL(SUM(credit), 0) AS total_credit'),
                'account_name',
                'bank_name',
                'account_number',
                'swift_code',
            )
            ->groupBy('id', 'label', 'opening_balance', 'account_name', 'bank_name', 'account_number', 'swift_code')
            ->get();


        return Inertia::render('Lawyer/ClientAccount/Index', [
            'clientAccountList' => $clientAccountList,
        ]);
    }

    public function show(Request $request)
    {
        if ($request->client_account != null) {
            return self::detail($request, $request->client_account);
        }

        $accList = DB::table(self::DB_NAME);

        $clientAccountList = ClientAccountList::query()
            ->where('bank_account_type_id', 'like', '1')
            ->paginate(10)
            ->withQueryString()
            ->through(fn($accList) => [
                'id' => $accList->id,
                'label' => $accList->label,
                'account_name' => $accList->account_name,
                'bank_name' => $accList->bank_name,
                'account_number' => $accList->account_number,
                'opening_balance' => $accList->opening_balance,
                'swift_code' => $accList->swift_code,
            ]);


        return Inertia::render('Lawyer/ClientAccount/Index', [
            'clientAccountList' => $clientAccountList,
        ]);
    }

    public function create($acc_number)
    {
        return Inertia::render('Lawyer/ClientAccount/Create', [
            'acc_number' => $acc_number,
        ]);
    }

    public function store(Request $request)
    {
        $filePath = null;

        try {
            $uniqueId = date('YmdHis') . uniqid();

            if (!$request->hasFile('upload')) {
                if (str_contains("funds in", $request->transaction_type)) {
                    ClientAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => $request->bank_account_id,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => "",
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'reference' => $request->reference,
                        'created_by' => Auth::id(),
                    ]);
                } else {

                    ClientAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => $request->bank_account_id,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => "",
                        'debit' => 0,
                        'credit' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'reference' => $request->reference,
                        'created_by' => Auth::id(),
                    ]);
                    FirmAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => 2,
                        'description' => $request->description,
                        'transaction_type' => 'funds in',
                        'document_number' => $request->document_number,
                        'upload' => "",
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->reference,
                        'transaction_id' => $uniqueId,
                        'created_by' => Auth::id(),
                    ]);
                }
            } else {
                $fileName = uniqid('TRANSACTION_') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(ClientAccount::UPLOAD_PATH, $fileName);

                $request->merge(['upload_filename' => $fileName]);

                if (str_contains("funds in", $request->transaction_type)) {
                    ClientAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => $request->bank_account_id,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => $filePath,
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'reference' => $request->reference,
                        'created_by' => Auth::id(),
                    ]);
                } else {
                    ClientAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => $request->bank_account_id,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => $filePath,
                        'debit' => 0,
                        'credit' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'reference' => $request->reference,
                        'created_by' => Auth::id(),
                    ]);
                    FirmAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => 2,
                        'description' => $request->description,
                        'transaction_type' => 'funds in',
                        'document_number' => $request->document_number,
                        'upload' => $filePath,
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->reference,
                        'transaction_id' => $uniqueId,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
            return redirect()->route('lawyer.client-accounts.show', ['client_account' => $request->bank_account_id])->with('successMessage', 'Successfully added new transaction.');
        } catch (\Exception $e) {
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            return back()->with('errorMessage', 'Failed to update invoice payment.' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $filePath = null;

        try {
            $clientAccount = ClientAccount::findOrFail($request->id); // Find the record by ID
            if ($request->existingDocument != null && $request->upload == null) {
                if (str_contains("funds in", $request->transaction_type)) {
                    $clientAccount->update([
                        'date' => $request->date,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'reference' => $request->reference,
                        'created_by' => Auth::id(),
                    ]);
                } else {
                    $clientAccount->update([
                        'date' => $request->date,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'debit' => 0,
                        'credit' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'reference' => $request->reference,
                        'created_by' => Auth::id(),
                    ]);
                    $itemInFirm = FirmAccount::query()
                        ->where('transaction_id', 'like', "{$request->transaction_id}")
                        ->update([
                            'date' => $request->date,
                            'description' => $request->description,
                            'transaction_type' => "funds in",
                            'document_number' => $request->document_number,
                            'debit' => $request->amount,
                            'credit' => 0,
                            'payment_method' => $request->payment_method,
                            'remarks' => $request->reference,
                        ]);
                }
                return redirect()->route('lawyer.client-accounts.show', ['client_account' => $request->bank_account_id])->with('successMessage', 'Successfully update the transaction.');
            } else {
                $fileName = uniqid('TRANSACTION_') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(ClientAccount::UPLOAD_PATH, $fileName);

                $request->merge(['upload_filename' => $fileName]);

                if (str_contains("funds in", $request->transaction_type)) {
                    $clientAccount->update([
                        'date' => $request->date,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => $filePath,
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'reference' => $request->reference,
                        'created_by' => Auth::id(),
                    ]);
                } else {
                    $clientAccount->update([
                        'date' => $request->date,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => $filePath,
                        'debit' => 0,
                        'credit' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'reference' => $request->reference,
                        'created_by' => Auth::id(),
                    ]);
                    $itemInFirm = FirmAccount::query()
                        ->where('transaction_id', 'like', "{$request->transaction_id}")
                        ->update([
                            'date' => $request->date,
                            'description' => $request->description,
                            'transaction_type' => "funds in",
                            'document_number' => $request->document_number,
                            'debit' => $request->amount,
                            'credit' => 0,
                            'payment_method' => $request->payment_method,
                            'remarks' => $request->reference,
                        ]);
                }

                return redirect()->route('lawyer.client-accounts.show', ['client_account' => $request->bank_account_id])->with('successMessage', 'Successfully update the transaction.');
            }
        } catch (\Exception $e) {
            if ($filePath != null && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            return back()->with('errorMessage', 'Failed to update invoice payment.' . $e->getMessage());
        }
    }

    public function detail(Request $request, $acc_number)
    {
        $filters = FacadesRequest::all(['search']);
        $accList = DB::table(self::DB_NAME);

        $bankAccount = BankAccounts::query()
            ->rightJoin("client_accounts as b", 'bank_account_type_id', '=', 'b.bank_account_id')
            ->select(
                'bank_accounts.id',
                'label',
                DB::raw('(IFNULL(SUM(debit), 0) - IFNULL(SUM(credit), 0)) + IFNULL(opening_balance, 0) AS opening_balance'),
                DB::raw('IFNULL(SUM(debit), 0) AS total_debit'),
                DB::raw('IFNULL(SUM(credit), 0) AS total_credit'),
                'account_name',
                'bank_name',
                'account_number',
                'swift_code',
            )
            ->where('bank_accounts.id', 'like', "%{$acc_number}%")
            ->groupBy('id', 'label', 'opening_balance', 'account_name', 'bank_name', 'account_number', 'swift_code')
            ->get();

        $clientAccounts = ClientAccount::query()
            ->where('bank_account_id', 'like', "%{$acc_number}%")
            ->paginate(10)
            ->withQueryString()
            ->through(fn($acc) => [
                'id' => $acc->id,
                'date' => $acc->date,
                'description' => $acc->description,
                'transaction_type' => $acc->transaction_type,
                'payment_method' => $acc->payment_method,
                'document_no' => $acc->document_number,
                'debit' => $acc->debit,
                'credit' => $acc->credit,
                'balance' => $acc->balance,
            ]);


        $acc = DB::table(self::DB_NAME)->sum('balance');

        $funds_in = DB::table(self::DB_NAME)
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds in')
            ->sum('debit');
        $funds_out = DB::table(self::DB_NAME)
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds out')
            ->sum('credit');

        return Inertia::render('Lawyer/ClientAccount/Details', [
            'clientAccounts' => $clientAccounts,
            'acc' => $acc,
            'acc_id' => $acc_number,
            'filters' => FacadesRequest::all('search'),
            'bank_accounts' => $bankAccount,
            'funds_in' => $funds_in,
            'funds_out' => $funds_out,
        ]);
    }

    public function detailFilter(Request $request, $acc_number, $t_type)
    {
        $filters = FacadesRequest::all(['search']);
        $accList = DB::table(self::DB_NAME);
        $filter_type = 0;
        if ($t_type == 0) {
            $filter_type = "funds out";
        } else {
            $filter_type = "funds in";
        }

        $bankAccount = BankAccounts::query()
            ->rightJoin("client_accounts as b", 'bank_account_type_id', '=', 'b.bank_account_id')
            ->select(
                'bank_accounts.id',
                'label',
                DB::raw('(IFNULL(SUM(debit), 0) - IFNULL(SUM(credit), 0)) + IFNULL(opening_balance, 0) AS opening_balance'),
                DB::raw('IFNULL(SUM(debit), 0) AS total_debit'),
                DB::raw('IFNULL(SUM(credit), 0) AS total_credit'),
                'account_name',
                'bank_name',
                'account_number',
                'swift_code',
            )
            ->where('bank_accounts.id', 'like', "%{$acc_number}%")
            ->groupBy('id', 'label', 'opening_balance', 'account_name', 'bank_name', 'account_number', 'swift_code')
            ->get();

        $clientAccounts = ClientAccount::query()
            ->where('bank_account_id', 'like', "%{$acc_number}%")
            ->where('transaction_type', 'like', "%{$filter_type}%")
            ->paginate(10)
            ->withQueryString()
            ->through(fn($acc) => [
                'id' => $acc->id,
                'date' => $acc->date,
                'description' => $acc->description,
                'transaction_type' => $acc->transaction_type,
                'payment_method' => $acc->payment_method,
                'document_no' => $acc->document_number,
                'debit' => $acc->debit,
                'credit' => $acc->credit,
                'balance' => $acc->balance,
            ]);


        $acc = DB::table(self::DB_NAME)->sum('balance');
        $funds_in = DB::table(self::DB_NAME)
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds in')
            ->sum('debit');
        $funds_out = DB::table(self::DB_NAME)
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds out')
            ->sum('credit');

        return Inertia::render('Lawyer/ClientAccount/Details', [
            'clientAccounts' => $clientAccounts,
            'acc' => $acc,
            'acc_id' => $acc_number,
            'filters' => FacadesRequest::all('search'),
            'bank_accounts' => $bankAccount,
            'funds_in' => $funds_in,
            'funds_out' => $funds_out,
        ]);
    }

    public function view(Request $request, $acc_number, $selected_item)
    {

        $clientAccounts = ClientAccount::query()
            ->where('id', 'like', "%{$selected_item}%")
            ->first();;

        return Inertia::render('Lawyer/ClientAccount/View', [
            'clientAccounts' => $clientAccounts,
            'acc_id' => $selected_item,
        ]);
    }

    public function edit(Request $request, $acc_number, $selected_item)
    {
        $clientAccounts = ClientAccount::query()
            ->where('id', 'like', "%{$selected_item}%")
            ->first();;

        return Inertia::render('Lawyer/ClientAccount/Edit', [
            'clientAccounts' => $clientAccounts,
            'acc_id' => $selected_item,
        ]);
    }

    public function destroy($id)
    {
        $clientAccount = ClientAccount::findOrFail($id);

        $bank_account_id = $clientAccount->bank_account_id;

        $itemInFirm = FirmAccount::query()
            ->where('transaction_id', 'like', "{clientAccount->transaction_id}")
            ->delete();

        $clientAccount->delete();

        return redirect()->route('lawyer.client-accounts.show', ['client_account' => $bank_account_id])->with('successMessage', 'Successfully deleted the account.');
    }

    public function totalBalance()
    {
        $acc = DB::table(self::DB_NAME)->sum('balance')->select('balance')->get();
        dd($acc);
    }
}
