<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveAppliedNotification extends Notification implements ShouldQueue
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
        $employeeName = $this->leave->employee->first_name . ' ' . $this->leave->employee->last_name;

        return (new MailMessage)
            ->subject('New Leave Application - ' . $employeeName)
            ->greeting('Hello Admin,')
            ->line($employeeName . ' has applied for a leave.')
            ->line('Leave Details:')
            ->line('• Type: ' . $this->leave->leave_type)
            ->line('• Duration: ' . $this->leave->start_date . ' to ' . $this->leave->end_date)
            ->line('• Reason: ' . ($this->leave->reason ?: 'No reason provided'))
            ->action('Review Leave Application', url('/leaves'))
            ->line('Thank you for using our HRM System!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $employeeName = $this->leave->employee->first_name . ' ' . $this->leave->employee->last_name;

        return [
            'type' => 'leave_applied',
            'leave_id' => $this->leave->id,
            'title' => 'New Leave Application',
            'message' => $employeeName . ' has applied for ' . $this->leave->leave_type . ' from ' . $this->leave->start_date . ' to ' . $this->leave->end_date . '.',
            'employee_id' => $this->leave->employee_id,
            'employee_name' => $employeeName,
        ];
    }
}
