<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClientAccount;
use App\Models\ClientAccountList;
use App\Models\BankAccount;
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

    public function show(Request $request)
    {
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
                }

                return redirect()->route('lawyer.client-accounts.show', ['client_account' => $request->bank_account_id])->with('message', 'Successfully added new transaction.');
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
                }

                return redirect()->route('lawyer.client-accounts.show', ['client_account' => $request->bank_account_id])->with('message', 'Successfully added new transaction.');
            }
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
            $clientAccount = ClientAccount::findOrFail($request->bank_account_id); // Find the record by ID
            if (!$request->hasFile('upload')) {
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
                }
                return redirect()->route('lawyer.client-accounts.show', ['client_account' => $request->bank_account_id])->with('message', 'Successfully update the transaction.');
            } else {
                $fileName = uniqid('TRANSACTION_') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = null;

                if (Storage::exists(ClientAccount::UPLOAD_PATH . $fileName)) {
                    // DO NOTHING
                } else {
                    $filePath = $request->file('upload')->storeAs(ClientAccount::UPLOAD_PATH, $fileName);
                }

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
                }

                return redirect()->route('lawyer.client-accounts.show', ['client_account' => $request->bank_account_id])->with('message', 'Successfully update the transaction.');
            }
        } catch (\Exception $e) {
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            return back()->with('errorMessage', 'Failed to update invoice payment.' . $e->getMessage());
        }
    }

    public function detail(Request $request, $acc_number)
    {
        $filters = FacadesRequest::all(['search']);
        $accList = DB::table(self::DB_NAME);

        $bankAccount = ClientAccountList::query()
            ->where('id', 'like', "%{$acc_number}%")
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
                'payment_method' => $accList->payment_menthod,
            ]);

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
                'document_no' => $acc->document_no,
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

        $bankAccount = ClientAccountList::query()
            ->where('id', 'like', "%{$acc_number}%")
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
                'document_no' => $acc->document_no,
                'debit' => $acc->debit,
                'credit' => $acc->credit,
                'balance' => $acc->balance,
            ]);


        $acc = DB::table(self::DB_NAME)->sum('balance');
        $funds_in = DB::table(self::DB_NAME)
            ->where('transaction_type', 'like', 'funds in')
            ->sum('balance');
        $funds_out = DB::table(self::DB_NAME)
            ->where('transaction_type', 'like', 'funds out')
            ->sum('balance');

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

        $clientAccount->delete();

        return redirect()->route('lawyer.client-accounts.show', ['client_account' => $bank_account_id])->with('message', 'Successfully deleted the account.');
    }

    public function totalBalance()
    {
        $acc = DB::table(self::DB_NAME)->sum('balance')->select('balance')->get();
        dd($acc);
    }
}
