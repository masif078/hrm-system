<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
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
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new task has been assigned to you.')
            ->line('Task Details:')
            ->line('• Title: ' . $this->task->title)
            ->line('• Project: ' . ($this->task->project ? $this->task->project->name : 'N/A'))
            ->line('• Priority: ' . $this->task->priority)
            ->line('• Due Date: ' . $this->task->due_date)
            ->action('View My Tasks', url('/employee/tasks'))
            ->line('Please make sure to review and update progress regularly.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'task_assigned',
            'task_id' => $this->task->id,
            'title' => 'New Task Assigned',
            'message' => 'A new task "' . $this->task->title . '" has been assigned to you. Due date: ' . $this->task->due_date . '.',
        ];
    }
}
