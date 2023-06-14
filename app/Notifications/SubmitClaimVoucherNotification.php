<?php

namespace App\Notifications;

use App\Models\ClaimVoucher;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmitClaimVoucherNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;
    protected $claimVoucher, $requester;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ClaimVoucher $claimVoucher, User $requester)
    {
        $this->claimVoucher = $claimVoucher;
        $this->requester = $requester;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'New claim voucher request',
            'body' => $this->requester->name . ' submitted claim voucher ' . $this->claimVoucher->ticket_number,
            'action_link' => '/admin/voucher-requests/' . $this->claimVoucher->id,
            'claim_voucher_id' => $this->claimVoucher->id,
        ];
    }
}
