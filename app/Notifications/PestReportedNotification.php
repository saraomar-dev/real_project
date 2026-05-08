<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PestReportedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */


    use Queueable;

    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    
public function toDatabase($notifiable)
{
    return [
        'title' => '⚠️ Community Pest Alert!',
        'message' => 'Pest (' . $this->report->pest_type . ') detected in Plot #' . $this->report->plot->plot_number . '. Please check your crops!',
        'report_id' => $this->report->id,
        'url' => route('pest.index'),
    ];
}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
{
    return ['database']; // ده معناه إن التنبيه هيتحفظ في جدول التنبيهات
}
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
