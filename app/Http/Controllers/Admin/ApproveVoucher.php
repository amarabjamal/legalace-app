<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ClaimVoucherStatusEnum;
use App\Enums\DisbursementItemStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClaimVoucherApprovalRequest;
use App\Models\ClaimVoucher;
use App\Notifications\ClaimVoucherApprovedNotification;
use Illuminate\Support\Facades\DB;

class ApproveVoucher extends Controller
{
    public function index()
    {

        return inertia('Admin/Voucher/Index', [
            'voucher_requests' => ClaimVoucher::pending()
                ->where('approver_user_id', '=', auth()->id())
                ->paginate(25)
                ->through(fn ($voucher) => [
                    'id' => $voucher->id,
                    'ticket_number' => $voucher->ticket_number,
                    'submission_date' => $voucher->submission_date ? $voucher->submission_date : 'N/A',
                    'amount' => $voucher->amount->formatTo('en_MY'),
                    'status' => ClaimVoucher::STATUS[$voucher->status->value],
                    'requester' => $voucher->requester->name,
                ]),
        ]);
    }

    public function show(ClaimVoucher $voucher_request)
    {

        return inertia('Admin/Voucher/Show', [
            'claim_voucher' => $voucher_request->toResource(),
            'items' => $voucher_request->items->map(fn($item) => 
                [
                    'name' => $item->name,
                    'description' => $item->description,
                    'amount' => $item->amount->formatTo('en-MY'),
                ]
            ),
        ]);
    }

    public function approveVoucher(StoreClaimVoucherApprovalRequest $request, ClaimVoucher $voucher_request)
    {
        if($voucher_request->status !== ClaimVoucherStatusEnum::Submitted) {
            return back()->with('errorMessage', "The claim voucher status is no longer 'Submitted'.");
        }

        $approvalInputs = $request->all();

        try {
            DB::transaction(function() use ($voucher_request, $approvalInputs) {
                $voucher_request->status = ClaimVoucherStatusEnum::Approved;
                $voucher_request->approval()->create($approvalInputs);
                $voucher_request->save();

                $voucher_request->items()->update(['status' => DisbursementItemStatusEnum::ApprovedForClaim]);
                
                $voucher_request->requester->notify(new ClaimVoucherApprovedNotification($voucher_request, auth()->user()));
            });

        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Failed to approve the claim voucher.');           
        }

        return back()->with('successMessage', 'The claim voucher has been approved.');
    }

    public function rejectVoucher()
    {
        
    }
}
