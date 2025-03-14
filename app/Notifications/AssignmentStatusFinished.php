<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignmentStatusFinished extends Notification
{
    use Queueable;

    protected $pdfPath;

    public function __construct($pdfPath)
    {
        $this->pdfPath = $pdfPath;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Certificate of Excellence')
            ->greeting('Congratulations, ' . $notifiable->name . '!')
            ->line('You have received the Certificate of Excellence in recognition of your outstanding performance.')
            ->line('Please find your certificate attached to this email.')
            ->attach($this->pdfPath, [
                'as' => 'Certificate_of_Excellence.pdf',
                'mime' => 'application/pdf',
            ])
            ->line('Best regards,')
            ->line('Our Team');
    }
}
