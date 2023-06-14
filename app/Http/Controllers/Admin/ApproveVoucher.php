<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClaimVoucher;

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
}
