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
use App\Notifications\SubmitClaimVoucherNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ClaimVoucherController extends Controller
{
    public function index()
    {
        return inertia('Lawyer/ClaimVoucher/Index', [
            'filters' => Request::all('search'),
            'claim_vouchers' => ClaimVoucher::where('requester_user_id', auth()->id())
                ->filter(Request::only('search'))
                ->paginate(25)
                ->withQueryString()
                ->through(fn ($voucher) => [
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

        return inertia('Lawyer/ClaimVoucher/Create', [
            'ticket_number' => ClaimVoucher::getNextTicketNumber(),
            'approvers' => User::admin()->notCurrentUser()->get(['id', 'name']),
            'reimbursable_items' => DisbursementItem::paidByLawyer()
                ->fromInvoiceCreatedByCurrentUser()
                ->get(['id', 'name', 'description', 'amount'])
                ->map(fn ($item) => [
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
            foreach($items as $item) {
                if  (
                        $item->status != DisbursementItemStatusEnum::PaidByClient
                        || $item->claim_voucher_id != null
                    ) 
                {
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
        } catch(\Exception $e) {
            DB::rollBack();

            return back()->with('errorMessage', 'Failed: ' . $e->getMessage());
        }
    }

    public function show(ClaimVoucher $claim_voucher)
    {
        
        return inertia('Lawyer/ClaimVoucher/Show', [
            'claim_voucher' => $claim_voucher->toResource(),
            'items' => $claim_voucher->items->map(fn($item) => 
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
        if($claim_voucher->status !== ClaimVoucherStatusEnum::Draft) {
            return back()->with('errorMessage', 'The claim voucher status is no longer a draft.');
        }

        try {
            DB::transaction(function() use ($claim_voucher) {
                $claim_voucher->submission_date = today();
                $claim_voucher->status = ClaimVoucherStatusEnum::Submitted;
                $claim_voucher->save();
                
                $claim_voucher->items()->update(['status' => DisbursementItemStatusEnum::PendingClaimApproval]);
            });

            $claim_voucher->approver->notify(new SubmitClaimVoucherNotification($claim_voucher, auth()->user()));
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to submit the claim voucher.');           
        }

        return back()->with('successMessage', 'The claim voucher has been submitted to ' . $claim_voucher->approver->name . '.');
    }
}
