<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\OperationalCost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
class OperationalCostController extends Controller
{
    public function index(Request $request)
    {
        $operationalCost = OperationalCost::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('details', 'like', "%{$search}%");
            })
            ->orderBy('id','desc')
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

        $acc = DB::table('operational_costs')->sum('amount');

        return Inertia::render('Lawyer/OperationalCost/Index',[
            'operationalCosts'=> $operationalCost,
            'filters' => $filters,
            'acc' => $acc,
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

        return redirect()->route('operational-cost.index')->with('message', 'Successfully added new record.');
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

        return redirect()->route('operational-cost.index')->with('message', 'Successfully updated the record.');
    }

    public function destroy(OperationalCost $operationalCost)
    {
        $operationalCost->delete();   

        return redirect()->route('operational-cost.index')->with('message', 'Successfully deleted the cost.');
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (! app()->environment(['local', 'testing']) && in_array($response->status(), [500, 503, 404, 403])) {
            return Inertia::render('Error', ['status' => $response->status()])
                ->toResponse($request)
                ->setStatusCode($response->status());
        } elseif ($response->status() === 419) {
            return back()->with([
                'message' => 'The page expired, please try again.',
            ]);
        }

        return $response;
    }
}
