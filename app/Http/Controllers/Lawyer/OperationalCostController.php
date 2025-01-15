<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOperationalCostRequest;
use App\Http\Requests\UpdateOperationalCostRequest;
use App\Models\OperationalCost;
use App\Models\FirmAccount;
use App\Models\OperationalCostTypes;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OperationalCostController extends Controller
{
    public function index(Request $request)
    {
        $non_recurring = OperationalCost::query()
            ->select('operational_costs.*', 'bank_accounts.account_name', 'bank_accounts.bank_name', 'bank_accounts.label')
            ->rightJoin('bank_accounts', 'operational_costs.bank_account_id', '=', 'bank_accounts.id')
            ->when($request->input('search'), function ($query, $search) {
                $amount = (int) $search;
                if ($amount) {
                    $query->where('operational_costs.amount', '>=', $amount);
                } else {

                    $query->where('operational_costs.details', 'like', "%{$search}%")
                        ->orWhere('bank_accounts.label', 'like', "%{$search}%")
                        ->orWhereDate('operational_costs.date', $search);
                    // ->orWhere('operational_costs.amount', '=', $search);
                }
            })
            ->where('is_recurring', 'like', "0")
            ->whereNotNull('operational_costs.id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn($cost) => [
                'details' => $cost->details,
                'amount' => $cost->amount,
                'payment_method' => $cost->payment_method,
                'document_number' => $cost->document_number,
                'date' => $cost->date,
                'label' => $cost->label,
            ]);

        // $recurring = OperationalCost::query()
        //     ->select('operational_costs.*', 'bank_accounts.account_name', 'bank_accounts.label')
        //     ->rightJoin('bank_accounts', 'operational_costs.bank_account_id', '=', 'bank_accounts.id')
        //     ->when($request->input('search'), function ($query, $search) {
        //         $query->where('name', 'like', "%{$search}%");
        //     })
        //     ->where('is_recurring', 'like', "1")
        //     ->whereNotNull('operational_costs.id')
        //     ->paginate(10)
        //     ->withQueryString()
        //     ->through(fn($cost) => [
        //         'id' => $cost->id,
        //         'details' => $cost->details,
        //         'amount' => $cost->amount,
        //         'is_recurring' => $cost->is_recurring,
        //         'recurring_period' => $cost->recurring_period,
        //         'is_paid' => $cost->is_paid,
        //         'label' => $cost->label,
        //         'date' => $cost->date,
        //     ]);

        $filters = $request->only(['search']);

        return Inertia::render('Lawyer/OperationalCost/Index', [
            'non_recurring' => $non_recurring,
            // 'recurring' => $recurring,
            'filters' => $filters,

        ]);
    }

    public function view($id)
    {
        $costs_item = OperationalCost::query()
            ->where('id', 'like', "%{$id}%")
            ->first();
        return Inertia::render('Lawyer/OperationalCost/View', [
            'costs_item' => $costs_item
        ]);
    }
    public function downloadFile($id)
    {
        try {
            // Find the firm account record
            $costs_item = OperationalCost::findOrFail($id);

            // Construct the full file path
            $filePath = storage_path('app/' . $costs_item->upload);
            $fileName = basename($costs_item->upload);

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

    public function create()
    {
        // $cost_types = OperationalCostTypes::find($id);
        $cost_types = OperationalCostTypes::all();

        return Inertia::render('Lawyer/OperationalCost/Create', [
            'cost_types' => $cost_types,
        ]);
    }

    public function storelama(Request $request)
    {

        $filePath = null;

        try {
            $fileName = null;

            if ($request->hasFile('upload')) {
                $fileName = uniqid('OPERATIONAL_COST') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(OperationalCost::UPLOAD_PATH, $fileName);

                $request->merge(['upload_filename' => $fileName]);
            }

            $uniqueId = date('YmdHis') . uniqid();

            OperationalCost::create([
                'date' => $request->date,
                'details' => $request->description,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                // 'is_recurring' => $request->is_recurring,
                'is_recurring' => 0,
                'recurring_period' => $request->frequency,
                'is_paid' => 1,
                'bank_account_id' => $request->account,
                'company_id' => $request->account,
                'first_payment_date' => $request->first_payment_date,
                'no_of_payment' => $request->no_of_payment,
                'upload' => $filePath,
                'document_number' => $request->document_number,
                'transaction_id' => $uniqueId,
                'created_by' => Auth::id(),
            ]);

            FirmAccount::create([
                'date' => $request->date,
                'bank_account_id' => $request->account,
                'description' => $request->description,
                'transaction_type' => "funds out",
                'transaction_id' => $uniqueId,
                'document_number' => $request->document_number,
                'upload' => $filePath,
                'debit' => 0,
                'credit' => $request->amount,
                'payment_method' => $request->payment_method,
                'remarks' => "",
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('lawyer.operational-cost.index')->with('message', 'Successfully added new operational cost.');
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to add operational cost.' . $e->getMessage());
        }
    }
    public function store(StoreOperationalCostRequest $request)
    {
        $filePath = null;
        $input = $request->all();

        try {
            if ($request->hasFile('upload')) {
                $fileName = uniqid('OPERATIONAL_COST') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(OperationalCost::UPLOAD_PATH, $fileName);

                $request->merge(['upload_filename' => $fileName]);
                $input['upload'] = $filePath;
            } else {
                $input['upload'] = "";
            }

            $uniqueId = date('YmdHis') . uniqid();

            $input['bank_account_id'] = $request->account;
            $input['company_id'] = $request->account;
            $input['is_recurring'] = 0;
            $input['is_paid'] = 1;
            $input['transaction_id'] = $uniqueId;
            $input['created_by'] = Auth::id();

            DB::transaction(function () use ($input) {
                OperationalCost::create($input);
            });

            FirmAccount::create([
                'date' => $request->date,
                'bank_account_id' => $request->account,
                'description' => $request->details,
                'transaction_type' => "funds out",
                'transaction_id' => $uniqueId,
                'document_number' => $request->document_number,
                'upload' => $filePath,
                'debit' => 0,
                'credit' => $request->amount,
                'payment_method' => $request->payment_method,
                'remarks' => "",
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('lawyer.operational-cost.index')->with('message', 'Successfully added new cost record.');
        } catch (\Exception $e) {
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            if ($request->fails()) {
                return Inertia::render('Lawyer/OperationalCost/Create', ['errors' => $request->errors()]);
            }

            return back()->with('errorMessage', 'Failed to add cost record.' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $costs_item = OperationalCost::query()
            ->where('id', 'like', "%{$id}%")
            ->first();

        $cost_types = OperationalCostTypes::all();

        return Inertia::render('Lawyer/OperationalCost/Edit', [
            'costs_item' => $costs_item,
            'cost_types' => $cost_types
        ]);
    }

    public function updateLama(Request $request)
    {
        $filePath = null;

        $item = OperationalCost::findOrFail($request->id); // Find the record by ID
        // if ($request->existingDocument != null && $request->upload == null) {
        if ($request->upload == null) {
            $item->update([
                'date' => $request->date,
                'details' => $request->description,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'is_recurring' => $request->is_recurring,
                'recurring_period' => $request->frequency,
                'is_paid' => 1,
                'bank_account_id' => $request->account,
                'company_id' => $request->account,
                'first_payment_date' => $request->first_payment_date,
                'no_of_payment' => $request->no_of_payment,
                'document_number' => $request->document_number,
                'created_by' => Auth::id(),
            ]);

            $itemInFirm = FirmAccount::query()
                ->where('transaction_id', 'like', "{$request->transaction_id}")
                ->update([
                    'date' => $request->date,
                    'bank_account_id' => $request->account,
                    'description' => $request->description,
                    'transaction_type' => "funds out",
                    'document_number' => $request->document_number,
                    'credit' => $request->amount,
                    'payment_method' => $request->payment_method,
                ]);
        } else {
            $fileName = uniqid('OPERATIONAL_COST') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
            $filePath = $request->file('upload')->storeAs(OperationalCost::UPLOAD_PATH, $fileName);
            $request->merge(['upload_filename' => $fileName]);
            $item->update([
                'date' => $request->date,
                'details' => $request->description,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'is_recurring' => $request->is_recurring,
                'recurring_period' => $request->frequency,
                'upload' => $filePath,
                'is_paid' => 1,
                'bank_account_id' => $request->account,
                'company_id' => $request->account,
                'first_payment_date' => $request->first_payment_date,
                'no_of_payment' => $request->no_of_payment,
                'document_number' => $request->document_number,
                'created_by' => Auth::id(),
            ]);

            $itemInFirm = FirmAccount::query()
                ->where('transaction_id', 'like', "{$request->transaction_id}")
                ->update([
                    'date' => $request->date,
                    'bank_account_id' => $request->account,
                    'description' => $request->description,
                    'upload' => $filePath,
                    'transaction_type' => "funds out",
                    'document_number' => $request->document_number,
                    'credit' => $request->amount,
                    'payment_method' => $request->payment_method,
                ]);
        }

        return redirect()->route('lawyer.operational-cost.index')->with('successMessage', 'Successfully update operational cost.');
    }
    public function update(UpdateOperationalCostRequest $request)
    {
        $filePath = null;
        $input = $request->all();

        try {
            $item = OperationalCost::findOrFail($request->id); // Find the record by ID

            // Handle file upload logic
            if ($request->hasFile('upload')) {
                // New file uploaded: process and store the new file
                $fileName = uniqid('OPERATIONAL_COST') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(OperationalCost::UPLOAD_PATH, $fileName);

                // Update the upload field with the new file path
                $input['upload'] = $filePath;
            } else {
                // No new file uploaded: retain the existing file path from existingDocument
                $input['upload'] = $request->existingDocument;
            }

            // Update the operational cost record
            $input['bank_account_id'] = $request->account;
            $input['company_id'] = $request->account;
            $input['is_paid'] = 1;
            $input['created_by'] = Auth::id();

            DB::transaction(function () use ($input, $item) {
                $item->update($input);
            });

            // Update the firm account record
            FirmAccount::query()
                ->where('transaction_id', 'like', "{$request->transaction_id}")
                ->update([
                    'date' => $request->date,
                    'bank_account_id' => $request->account,
                    'description' => $request->details,
                    'upload' => $input['upload'],
                    'transaction_type' => "funds out",
                    'document_number' => $request->document_number,
                    'credit' => $request->amount,
                    'payment_method' => $request->payment_method,
                ]);

            return redirect()->route('lawyer.operational-cost.index')->with('successMessage', 'Successfully updated operational cost.');
        } catch (\Exception $e) {
            // Clean up the uploaded file if an error occurs
            if ($filePath != null && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            // Handle validation errors
            if ($request->fails()) {
                return Inertia::render('Lawyer/OperationalCost/Edit', ['errors' => $request->errors()]);
            }

            return back()->with('errorMessage', 'Failed to update operational cost: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $operationalCost = OperationalCost::findOrFail($id);

        FirmAccount::query()
            ->where('transaction_id', 'like', '%' . $operationalCost->transaction_id . '%')
            ->delete();

        $operationalCost->delete();

        return redirect()->route('lawyer.operational-cost.index')->with('successMessage', 'Successfully deleted the cost.');
    }
}
