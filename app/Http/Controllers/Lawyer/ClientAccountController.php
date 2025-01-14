<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientAccountRequest;
use App\Http\Requests\UpdateClientAccountRequest;
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
            ->rightJoin("client_accounts as b", 'bank_accounts.id', '=', 'b.bank_account_id')
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

    public function storeLama(Request $request)
    {
        $filePath = null;
        // $filePath = "";

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
                        'transaction_id' => $uniqueId,
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
                        'transaction_id' => $uniqueId,
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
            if ($request->fails()) {
                return Inertia::render('Lawyer/ClientAccount/Create', ['errors' => $request->errors()]);
            }

            return back()->with('errorMessage', 'Failed to add transactions.' . $e->getMessage());
        }
    }
    public function store(StoreClientAccountRequest $request)
    {
        $filePath = null;
        $input1 = $request->all();
        // $input2 = $request->all();

        try {
            $uniqueId = date('YmdHis') . uniqid();

            if (!$request->hasFile('upload')) {
                if (str_contains("funds in", $request->transaction_type)) {
                    $input1['upload'] = "";
                    $input1['debit'] = $request->amount;
                    $input1['credit'] = 0;
                    DB::transaction(function () use ($input1) {
                        ClientAccount::create($input1);
                    });
                } else {
                    $input1['upload'] = "";
                    $input1['debit'] = 0;
                    $input1['credit'] = $request->amount;
                    $input1['transaction_id'] = $uniqueId;
                    DB::transaction(function () use ($input1) {
                        ClientAccount::create($input1);
                    });
                    // $input2['upload'] = "";
                    // $input2['debit'] = 0;
                    // $input2['credit'] = $request->amount;
                    // $input2['bank_account_id'] = 2;
                    // $input2['transaction_type'] = 'funds in';
                    // $input2['transaction_id'] = $uniqueId;
                    // DB::transaction(function () use ($input2) {
                    //     FirmAccount::create($input2);
                    // });
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
                    $input1['upload'] = $filePath;
                    $input1['debit'] = $request->amount;
                    $input1['credit'] = 0;
                    DB::transaction(function () use ($input1) {
                        ClientAccount::create($input1);
                    });
                } else {
                    $input1['upload'] = $filePath;
                    $input1['debit'] = 0;
                    $input1['credit'] = $request->amount;
                    $input1['transaction_id'] = $uniqueId;
                    DB::transaction(function () use ($input1) {
                        ClientAccount::create($input1);
                    });
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
            if ($request->fails()) {
                return Inertia::render('Lawyer/ClientAccount/Create', ['errors' => $request->errors()]);
            }

            return back()->with('errorMessage', 'Failed to update invoice payment.' . $e->getMessage());
        }
    }

    public function updateLama(Request $request)
    {
        $filePath = null;

        try {
            $clientAccount = ClientAccount::findOrFail($request->id); // Find the record by ID
            // if ($request->existingDocument != null && $request->upload == null) {
            if ($request->upload == null) {
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
    public function update(UpdateClientAccountRequest $request)
    {
        $filePath = null;
        $input1 = $request->all();

        try {
            $clientAccount = ClientAccount::findOrFail($request->id); // Find the record by ID
            // if ($request->existingDocument != null && $request->upload == null) {
            if ($request->upload == null) {
                if (str_contains("funds in", $request->transaction_type)) {

                    $input1['debit'] = $request->amount;
                    $input1['credit'] = 0;
                    DB::transaction(function () use ($input1, $clientAccount) {
                        $clientAccount->update($input1);
                    });
                } else {
                    $input1['debit'] = 0;
                    $input1['credit'] = $request->amount;
                    DB::transaction(function () use ($input1, $clientAccount) {
                        $clientAccount->update($input1);
                    });
                    $itemFirm = FirmAccount::query()
                        ->where('transaction_id', 'like', "{$clientAccount->transaction_id}")
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
                    $input['upload'] = $filePath;
                    $input1['debit'] = $request->amount;
                    $input1['credit'] = 0;
                    DB::transaction(function () use ($input1, $clientAccount) {
                        $clientAccount->update($input1);
                    });
                } else {
                    $input['upload'] = $filePath;
                    $input1['debit'] = 0;
                    $input1['credit'] = $request->amount;
                    DB::transaction(function () use ($input1, $clientAccount) {
                        $clientAccount->update($input1);
                    });
                    $itemFirm = FirmAccount::query()
                        ->where('transaction_id', 'like', "{$clientAccount->transaction_id}")
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

            return back()->with('errorMessage', 'Failed to update transaction records.' . $e->getMessage());
        }
    }

    public function detail(Request $request, $acc_number)
    {
        $filters = FacadesRequest::all(['search']);
        $accList = DB::table(self::DB_NAME);

        $bankAccount = BankAccounts::query()
            ->rightJoin("client_accounts as b", 'bank_accounts.id', '=', 'b.bank_account_id')
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


        // Get the selected period from the request
        $selectedPeriod = $request->input('period', 'this_month'); // Default to 'this_month'

        // Calculate the start and end dates based on the selected period
        $startDate = now();
        $endDate = now();

        switch ($selectedPeriod) {
            case 'this_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            case 'next_month':
                $startDate = now()->addMonth()->startOfMonth();
                $endDate = now()->addMonth()->endOfMonth();
                break;
            case 'last_3_months':
                $startDate = now()->subMonths(3)->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();     // End of the previous month
                break;
            case 'last_6_months':
                $startDate = now()->subMonths(6)->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();     // End of the previous month
                break;
            case 'next_3_months':
                $startDate = now()->startOfMonth();
                $endDate = now()->addMonths(3)->endOfMonth();
                break;
            case 'next_6_months':
                $startDate = now()->startOfMonth();
                $endDate = now()->addMonths(6)->endOfMonth();
                break;
            case 'last_year':
                $startDate = now()->subYear()->startOfYear();
                $endDate = now()->subYear()->endOfYear();
                break;
            case 'next_year':
                $startDate = now()->addYear()->startOfYear();
                $endDate = now()->addYear()->endOfYear();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
        }

        $funds_in = DB::table(self::DB_NAME)
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds in')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('debit');
        $funds_out = DB::table(self::DB_NAME)
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds out')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('credit');

        return Inertia::render('Lawyer/ClientAccount/Details', [
            'clientAccounts' => $clientAccounts,
            'acc' => $acc,
            'acc_id' => $acc_number,
            'filters' => FacadesRequest::all('search'),
            'bank_accounts' => $bankAccount,
            'funds_in' => $funds_in,
            'funds_out' => $funds_out,
            'selectedPeriod' => $selectedPeriod,
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

    public function downloadFile($id)
    {
        try {
            // Find the firm account record
            $clientAccount = ClientAccount::findOrFail($id);

            // Construct the full file path
            $filePath = storage_path('app/' . $clientAccount->upload);
            $fileName = basename($clientAccount->upload);

            // Check if the file exists
            if (!file_exists($filePath)) {
                throw new \Exception('File not found');
            }

            // Return the file as a download response
            return response()->download($filePath, $fileName);
        } catch (\Exception $e) {
            // Log the error
            // Log::error($e->getMessage());

            // Return a JSON response with the error message
            // Return an Inertia response with the error message
            return Inertia::render('Error', [
                'error' => $e->getMessage(),
            ]);
        }
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

        if ($clientAccount->transaction_id != null) {
            FirmAccount::query()
                ->where('transaction_id', $clientAccount->transaction_id)
                ->delete();
        }

        $clientAccount->delete();

        return redirect()->route('lawyer.client-accounts.show', ['client_account' => $bank_account_id])->with('successMessage', 'Successfully deleted the account.');
    }

    public function totalBalance()
    {
        $acc = DB::table(self::DB_NAME)->sum('balance')->select('balance')->get();
        dd($acc);
    }
}
