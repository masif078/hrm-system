<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leave;

    /**
     * Create a new notification instance.
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->leave->status;
        $subject = 'Leave Application ' . $status;

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your leave application for ' . $this->leave->leave_type . ' (' . $this->leave->start_date . ' to ' . $this->leave->end_date . ') has been ' . strtolower($status) . '.')
            ->line('Admin remarks: ' . ($this->leave->admin_remarks ?: 'No remarks provided.'))
            ->action('View My Leaves', url('/employee/leaves'))
            ->line('Thank you for using our HRM System!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'leave_status',
            'leave_id' => $this->leave->id,
            'title' => 'Leave Status Updated',
            'status' => $this->leave->status,
            'message' => 'Your leave request for ' . $this->leave->leave_type . ' has been ' . $this->leave->status . '.',
        ];
    }
}
