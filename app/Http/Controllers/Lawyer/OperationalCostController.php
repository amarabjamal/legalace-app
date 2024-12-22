<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\OperationalCost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperationalCostController extends Controller
{
    public function index(Request $request)
    {
        $operationalCost = OperationalCost::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString()
            ->through(fn($cost) => [
                'id' => $cost->id,
                'details' => $cost->details,
                'amount' => $cost->amount,
                'is_recurring' => $cost->is_recurring,
                'recurring_period' => $cost->recurring_period,
                'is_paid' => $cost->is_paid,
            ]);
        $filters = $request->only(['search']);

        return Inertia::render('Lawyer/OperationalCost/Index', [
            'operationalCosts' => $operationalCost,
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
            if ($request->hasFile('upload')) {
                $fileName = uniqid('OPERATIONAL_COST') . '_' . date('Ymd') . '_' . time() . '.' . $request->file('upload')->extension();
                $filePath = $request->file('upload')->storeAs(OperationalCost::UPLOAD_PATH, $fileName);

                $request->merge(['upload_filename' => $fileName]);

                OperationalCost::create([
                    'details' => $request->description,
                    'amount' => $request->amount,
                    'is_recurring' => $request->is_recurring,
                    'recurring_period' => $request->frequency,
                    'is_paid' => 1,
                    'bank_account_id' => $request->account,
                    'company_id' => $request->account,
                    'first_payment_date' => $request->first_payment_date,
                    'no_of_payment' => $request->no_of_payment,
                    'upload' => $filePath,
                    'document_number' => $request->document_number,
                    'created_by' => Auth::id(),
                ]);

                return redirect()->route('lawyer.operational-cost.index')->with('message', 'Successfully added new operational cost.');
            }
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to update operational cost.' . $e->getMessage());
        }
    }

    public function edit(OperationalCost $operationalCost) {}

    public function update(Request $request, OperationalCost $operationalCost) {}

    public function destroy(OperationalCost $operationalCost)
    {
        $operationalCost->delete();

        return redirect()->route('operational-cost.index')->with('message', 'Successfully deleted the cost.');
    }
}
