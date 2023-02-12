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
                'details'=>$cost->details,
                'amount' => $cost->amount,
                'is_recurring' => $cost->is_recurring,
                'recurring_period' => $cost->recurring_period,
                'is_paid' => $cost->is_paid,
            ]);
        $filters = $request->only(['search']);

        return Inertia::render('Lawyer/OperationalCost/Index',[
            'operationalCosts'=> $operationalCost,
            'filters' => $filters,
            
        ]);
    }

    public function create()
    {
        return Inertia::render('Lawyer/OperationalCost/Create');
    }

    public function store(Request $request)
    {
        OperationalCost::create([
            'details'=>$request->details,
            'amount' => $request->amount,
            'is_recurring' => $request->is_recurring,
            'recurring_period' => $request->recurring_period,
            'is_paid' => $request->is_paid,
            'bank_account_id'=>$request->bank_account_id,
            'created_by'=>Auth::id(),
        ]);

        return redirect()->route('lawyer.operational-cost.index')->with('message', 'Successfully added new operational cost.');
    }

    public function edit(OperationalCost $operationalCost)
    {
        return Inertia::render('Lawyer/OperationalCost/Edit', [
            'operationalCosts' => $operationalCost
        ]);
    }

    public function update(Request $request, OperationalCost $operationalCost)
    {
        $operationalCost->update([
            'details'=>$request->details,
            'amount' => $request->amount,
            'is_recurring' => $request->is_recurring,
            'recurring_period' => $request->recurring_period,
            'is_paid' => $request->is_paid,
            'bank_account_id'=>$request->bank_account_id,
        ]);

        return redirect()->route('lawyer.operational-cost.index')->with('message', 'Successfully updated the record.');
    }

    public function destroy(OperationalCost $operationalCost)
    {
        $operationalCost->delete();   

        return redirect()->route('lawyer.operational-cost.index')->with('message', 'Successfully deleted the cost.');
    }
}
