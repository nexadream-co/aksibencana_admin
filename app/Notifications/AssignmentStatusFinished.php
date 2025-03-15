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
            ->subject('Sertifikat Penghargaan')
            ->greeting('Selamat, ' . $notifiable->name . '!')
            ->line('Atas kontribusi luar biasa sebagai relawan dalam upaya pemulihan pasca-bencana di Indonesia.')
            ->attach($this->pdfPath, [
                'as' => 'sertifikat-penghargaan.pdf',
                'mime' => 'application/pdf',
            ])
            ->line('Salam hangat,')
            ->line('Aksi Bencana');
    }
}
