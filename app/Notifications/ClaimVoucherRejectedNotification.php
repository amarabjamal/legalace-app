<?php

namespace App\Notifications;

use App\Models\ClaimVoucher;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClaimVoucherRejectedNotification extends Notification
{
    use Queueable;
    protected $claimVoucher, $approver;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ClaimVoucher $claimVoucher, User $approver)
    {
        $this->claimVoucher = $claimVoucher;
        $this->approver = $approver;
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
            'title' => $this->claimVoucher->ticket_number . ' claim voucher is rejected',
            'body' => $this->approver->name . ' has rejected your claim.',
            'action_link' => '/lawyer/claim-vouchers/' . $this->claimVoucher->id,
        ];
    }
}
