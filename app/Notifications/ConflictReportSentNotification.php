<?php

namespace App\Notifications;

use App\Models\CaseFile;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConflictReportSentNotification extends Notification
{
    use Queueable;
    protected $caseFile, $creator;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CaseFile $caseFile, User $creator)
    {
        $this->caseFile = $caseFile;
        $this->creator = $creator;
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
            'title' => 'New Conflict Report received',
            'body' => $this->creator->name . ' sent you a conflict report on ' . $this->caseFile->file_number . '. Please review the report for your perusal.',
            'action_link' => '/lawyer/case-files/' . $this->caseFile->id,
        ];
    }
}
