<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\OperationalCost;
use App\Models\FirmAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperationalCostController extends Controller
{
    public function index(Request $request)
    {
        $non_recurring = OperationalCost::query()
            ->select('operational_costs.*', 'bank_accounts.account_name', 'bank_accounts.bank_name', 'bank_accounts.label')
            ->rightJoin('bank_accounts', 'operational_costs.bank_account_id', '=', 'bank_accounts.id')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->where('is_recurring', 'like', "0")
            ->whereNotNull('operational_costs.id')
            ->paginate(10)
            ->withQueryString();

        $recurring = OperationalCost::query()
            ->select('operational_costs.*', 'bank_accounts.account_name', 'bank_accounts.label')
            ->rightJoin('bank_accounts', 'operational_costs.bank_account_id', '=', 'bank_accounts.id')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->where('is_recurring', 'like', "1")
            ->whereNotNull('operational_costs.id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn($cost) => [
                'id' => $cost->id,
                'details' => $cost->details,
                'amount' => $cost->amount,
                'is_recurring' => $cost->is_recurring,
                'recurring_period' => $cost->recurring_period,
                'is_paid' => $cost->is_paid,
                'label' => $cost->label,
                'date' => $cost->date,
            ]);

        $filters = $request->only(['search']);

        return Inertia::render('Lawyer/OperationalCost/Index', [
            'non_recurring' => $non_recurring,
            'recurring' => $recurring,
            'filters' => $filters,

        ]);
    }

    public function create()
    {
        return Inertia::render('Lawyer/OperationalCost/Create');
    }

    public function store(Request $request)
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

    public function edit($id)
    {
        $costs_item = OperationalCost::query()
            ->where('id', 'like', "%{$id}%")
            ->first();
        return Inertia::render('Lawyer/OperationalCost/Edit', [
            'costs_item' => $costs_item
        ]);
    }

    public function update(Request $request)
    {
        $item = OperationalCost::findOrFail($request->id); // Find the record by ID
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

        return redirect()->route('lawyer.operational-cost.index')->with('message', 'Successfully update operational cost.');
    }

    public function destroy($id)
    {
        $operationalCost = OperationalCost::findOrFail($id);

        $operationalCost->delete();


        return redirect()->route('lawyer.operational-cost.index')->with('message', 'Successfully deleted the cost.');
    }
}
