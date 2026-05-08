<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Notifications\ComplaintNotification;
use Illuminate\Notifications\Notification;

class ComplaintNotification extends Notification
{
    use Queueable;

    public $complaint;

    public function __construct($complaint)
    {
        $this->complaint = $complaint;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => '🚨 New Complaint!',
            'message' => 'Farmer ' . $this->complaint->user->name .
                        ' reported: ' . $this->complaint->title,

            'url' => route('dashboard.show'),
        ];
    }
}