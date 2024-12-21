<?php

namespace App\Notifications;

use App\Models\Logistic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class LogisticStatusUpdated extends Notification
{
    use Queueable;

    private Logistic $logistic;

    /**
     * Create a new notification instance.
     */
    public function __construct(Logistic $logistic)
    {
        $this->logistic = $logistic;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
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
            'title' => 'Status Logistik',
            'body' => $this->statusMessage($this->logistic->status),
            'data' => $this->logistic,
            'type' => 'logistic'
        ];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: 'Status Logistik',
            body: $this->statusMessage($this->logistic->status),
        )))
            ->data(['data' => @json_encode($this->logistic), 'type' => 'logistic']);
    }

    private function statusMessage($status): string
    {
        if ($status == 'active') {
            return 'Logistik anda telah diterima oleh Aksi Bencana, terimakasih yang sebesar-besarnya!';
        } else if ($status == 'rejected') {
            return 'Mohon maaf, logistik anda tidak bisa kami diterima';
        } else {
            return 'Mohon maaf, terjadi kesalahan dalam penerimaan logistik';
        }
    }
}
