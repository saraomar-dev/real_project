<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PestReportedNotification extends Notification
{
    use Queueable;

    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['database']; // بنخزنها في الداتابيز عشان تظهر في الناف بار
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => '⚠️ Community Pest Alert!',
            'message' => 'Pest (' . $this->report->pest_type . ') detected in Plot #' . $this->report->plot->plot_number,
            'url' => route('pest.index'), // ده اللينك اللي هيفتح لما تدوسي عليها
        ];
    }
}