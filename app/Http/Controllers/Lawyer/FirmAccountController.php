<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFirmAccountRequest;
use App\Http\Requests\UpdateFirmAccountRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\FirmAccount;
use App\Models\FirmAccountList;
use App\Models\BankAccounts;
use App\Models\OperationalCost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;

class FirmAccountController extends Controller
{
    public function index(Request $request)
    {
        $accList = DB::table('firm_account');

        $firmAccountList = BankAccounts::query()
            ->rightJoin('firm_account as b', 'bank_accounts.id', '=', 'b.bank_account_id')
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

        return Inertia::render('Lawyer/FirmAccount/Index', [
            'firmAccountList' => $firmAccountList,
        ]);
    }

    public function show(Request $request)
    {
        if ($request->firm_account != null) {
            return self::detail($request, $request->firm_account);
        }

        $accList = DB::table('firm_account');

        $firmAccountList = FirmAccountList::query()
            ->where('bank_account_type_id', 'like', '2')
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


        return Inertia::render('Lawyer/FirmAccount/Index', [
            'firmAccountList' => $firmAccountList,
        ]);
    }

    public function create($acc_number)
    {
        return Inertia::render('Lawyer/FirmAccount/Create', [
            'acc_number' => $acc_number,
        ]);
    }

    public function storeLama(Request $request)
    {
        $filePath = null;

        try {
            if (!$request->hasFile('upload')) {
                if (str_contains("funds in", $request->transaction_type)) {
                    FirmAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => $request->bank_account_id,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => "",
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->remarks,
                        'created_by' => Auth::id(),
                    ]);
                } else {
                    FirmAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => $request->bank_account_id,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => "",
                        'debit' => 0,
                        'credit' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->remarks,
                        'created_by' => Auth::id(),
                    ]);
                }
            } else {
                $fileName = uniqid('TRANSACTION_') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(FirmAccount::UPLOAD_PATH, $fileName);

                $request->merge(['upload_filename' => $fileName]);

                if (str_contains("funds in", $request->transaction_type)) {
                    FirmAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => $request->bank_account_id,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => $filePath,
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->remarks,
                        'created_by' => Auth::id(),
                    ]);
                } else {
                    FirmAccount::create([
                        'date' => $request->date,
                        'bank_account_id' => $request->bank_account_id,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => $filePath,
                        'debit' => 0,
                        'credit' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->remarks,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
            return redirect()->route('lawyer.firm-accounts.show', ['firm_account' => $request->bank_account_id])->with('successMessage', 'Successfully added new transaction.');
        } catch (\Exception $e) {
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            return back()->with('errorMessage', 'Failed to update invoice payment.' . $e->getMessage());
        }
    }
    public function store(StoreFirmAccountRequest $request)
    {
        $filePath = null;
        $input = $request->all();

        try {
            if (!$request->hasFile('upload')) {
                if (str_contains("funds in", $request->transaction_type)) {
                    $input['upload'] = "";
                    $input['debit'] = $request->amount;
                    $input['credit'] = 0;
                    DB::transaction(function () use ($input) {
                        FirmAccount::create($input);
                    });
                } else {
                    $input['upload'] = "";
                    $input['debit'] = 0;
                    $input['credit'] = $request->amount;
                    DB::transaction(function () use ($input) {
                        FirmAccount::create($input);
                    });
                }
            } else {
                $fileName = uniqid('TRANSACTION_') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(FirmAccount::UPLOAD_PATH, $fileName);

                $request->merge(['upload_filename' => $fileName]);

                if (str_contains("funds in", $request->transaction_type)) {
                    $input['upload'] = $filePath;
                    $input['debit'] = $request->amount;
                    $input['credit'] = 0;
                    DB::transaction(function () use ($input) {
                        FirmAccount::create($input);
                    });
                } else {
                    $input['upload'] = $filePath;
                    $input['debit'] = 0;
                    $input['credit'] = $request->amount;
                    DB::transaction(function () use ($input) {
                        FirmAccount::create($input);
                    });
                }
            }
            return redirect()->route('lawyer.firm-accounts.show', ['firm_account' => $request->bank_account_id])->with('successMessage', 'Successfully added new transaction.');
        } catch (\Exception $e) {
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            if ($request->fails()) {
                return Inertia::render('Lawyer/FirmAccount/Create', ['errors' => $request->errors()]);
            }

            return back()->with('errorMessage', 'Failed to update invoice payment.' . $e->getMessage());
        }
    }

    public function updateLama(Request $request)
    {
        $filePath = null;

        try {
            $firmAccount = FirmAccount::findOrFail($request->id); // Find the record by ID
            // if ($request->existingDocument != null && $request->upload == null) {
            if ($request->upload == null) {
                if (str_contains("funds in", $request->transaction_type)) {
                    $firmAccount->update([
                        'date' => $request->date,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->remarks,
                        'created_by' => Auth::id(),
                    ]);
                } else {
                    $firmAccount->update([
                        'date' => $request->date,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'debit' => 0,
                        'credit' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->remarks,
                        'created_by' => Auth::id(),
                    ]);
                }
            } else {
                $filePath = null;

                $fileName = uniqid('TRANSACTION_') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(FirmAccount::UPLOAD_PATH, $fileName);

                $request->merge(['upload_filename' => $fileName]);

                if (str_contains("funds in", $request->transaction_type)) {
                    $firmAccount->update([
                        'date' => $request->date,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => $filePath,
                        'debit' => $request->amount,
                        'credit' => 0,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->remarks,
                        'created_by' => Auth::id(),
                    ]);
                } else {
                    $firmAccount->update([
                        'date' => $request->date,
                        'description' => $request->description,
                        'transaction_type' => $request->transaction_type,
                        'document_number' => $request->document_number,
                        'upload' => $filePath,
                        'debit' => 0,
                        'credit' => $request->amount,
                        'payment_method' => $request->payment_method,
                        'remarks' => $request->remarks,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
            return redirect()->route('lawyer.firm-accounts.show', ['firm_account' => $request->bank_account_id])->with('successMessage', 'Successfully update the transaction.');
        } catch (\Exception $e) {
            if ($filePath != null && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            return back()->with('errorMessage', 'Failed to update invoice payment.' . $e->getMessage());
        }
    }
    public function update(UpdateFirmAccountRequest $request)
    {
        $filePath = null;
        $input = $request->all();

        try {
            $firmAccount = FirmAccount::findOrFail($request->id); // Find the record by ID
            // if ($request->existingDocument != null && $request->upload == null) {
            if ($request->upload == null) {
                if (str_contains("funds in", $request->transaction_type)) {

                    $input['debit'] = $request->amount;
                    $input['credit'] = 0;
                    DB::transaction(function () use ($input, $firmAccount) {
                        $firmAccount->update($input);
                    });
                } else {
                    $input['debit'] = 0;
                    $input['credit'] = $request->amount;
                    DB::transaction(function () use ($input, $firmAccount) {
                        $firmAccount->update($input);
                    });
                }
            } else {
                $filePath = null;

                $fileName = uniqid('TRANSACTION_') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(FirmAccount::UPLOAD_PATH, $fileName);

                $request->merge(['upload_filename' => $fileName]);

                if (str_contains("funds in", $request->transaction_type)) {
                    $input['upload'] = $filePath;
                    $input['debit'] = $request->amount;
                    $input['credit'] = 0;
                    DB::transaction(function () use ($input, $firmAccount) {
                        $firmAccount->update($input);
                    });
                } else {
                    $input['upload'] = $filePath;
                    $input['debit'] = 0;
                    $input['credit'] = $request->amount;
                    DB::transaction(function () use ($input, $firmAccount) {
                        $firmAccount->update($input);
                    });
                }
            }
            return redirect()->route('lawyer.firm-accounts.show', ['firm_account' => $request->bank_account_id])->with('successMessage', 'Successfully update the transaction.');
        } catch (\Exception $e) {
            if ($filePath != null && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            if ($request->fails()) {
                return Inertia::render('Lawyer/FirmAccount/Edit', ['errors' => $request->errors()]);
            }

            return back()->with('errorMessage', 'Failed to update invoice payment.' . $e->getMessage());
        }
    }

    public function detail(Request $request, $acc_number)
    {
        $filters = FacadesRequest::all(['search']);
        $accList = DB::table('firm_account');

        $bankAccount = BankAccounts::query()
            ->rightJoin('firm_account as b', 'bank_account_type_id', '=', 'b.bank_account_id')
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

        $firmAccounts = FirmAccount::query()
            ->where('bank_account_id', 'like', "%{$acc_number}%")
            ->orWhere('description', 'like', "%payment%")
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
                'transaction_id' => $acc->transaction_id,
            ]);


        $acc = DB::table('firm_account')->sum('balance');

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

        $funds_in = DB::table('firm_account')
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds in')
            ->whereBetween('date', [$startDate, $endDate])
            // ->whereMonth('date', now()->month) // Filter by current month
            // ->whereYear('date', now()->year)   // Filter by current year
            ->sum('debit');
        $funds_out = DB::table('firm_account')
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds out')
            ->whereBetween('date', [$startDate, $endDate])
            // ->whereMonth('date', now()->month) // Filter by current month
            // ->whereYear('date', now()->year)   // Filter by current year
            ->sum('credit');

        return Inertia::render('Lawyer/FirmAccount/Details', [
            'firmAccounts' => $firmAccounts,
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
        $accList = DB::table('firm_account');
        $filter_type = 0;
        if ($t_type == 0) {
            $filter_type = "funds out";
        } else {
            $filter_type = "funds in";
        }

        $bankAccount = BankAccounts::query()
            ->rightJoin('firm_account as b', 'bank_account_type_id', '=', 'b.bank_account_id')
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

        $firmAccounts = FirmAccount::query()
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


        $acc = DB::table('firm_account')->sum('balance');
        $funds_in = DB::table('firm_account')
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds in')
            ->sum('debit');
        $funds_out = DB::table('firm_account')
            ->where('bank_account_id', 'like', "%{$acc_number}")
            ->where('transaction_type', 'like', 'funds out')
            ->sum('credit');

        return Inertia::render('Lawyer/FirmAccount/Details', [
            'firmAccounts' => $firmAccounts,
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

        $firmAccounts = FirmAccount::query()
            ->where('id', 'like', "%{$selected_item}%")
            ->first();;

        return Inertia::render('Lawyer/FirmAccount/View', [
            'firmAccounts' => $firmAccounts,
            'acc_id' => $selected_item,
        ]);
    }
    public function downloadFile($id)
    {
        try {
            // Find the firm account record
            $firmAccount = FirmAccount::findOrFail($id);

            // Construct the full file path
            $filePath = storage_path('app/' . $firmAccount->upload);
            $fileName = basename($firmAccount->upload);

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
        $firmAccounts = FirmAccount::query()
            ->where('id', 'like', "%{$selected_item}%")
            ->first();;

        return Inertia::render('Lawyer/FirmAccount/Edit', [
            'firmAccounts' => $firmAccounts,
            'acc_id' => $selected_item,
        ]);
    }

    public function destroy($id)
    {
        $firmAccount = FirmAccount::findOrFail($id);

        $bank_account_id = $firmAccount->bank_account_id;

        OperationalCost::query()
            ->where('transaction_id', 'like', '%' . $firmAccount->transaction_id . '%')
            ->delete();

        $firmAccount->delete();

        return redirect()->route('lawyer.firm-accounts.show', ['firm_account' => $bank_account_id])->with('successMessage', 'Successfully deleted the account.');
    }

    public function totalBalance()
    {
        $acc = DB::table('firm_account')->sum('balance')->select('balance')->get();
        dd($acc);
    }
}
