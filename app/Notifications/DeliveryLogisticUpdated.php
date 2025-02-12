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

class DeliveryLogisticUpdated extends Notification
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
            'title' => 'Penyaluran Logistik',
            'body' => $this->statusMessage(),
            'data' => $this->logistic,
            'type' => 'delivery_logistic'
        ];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: 'Penyaluran Logistik',
            body: $this->statusMessage(),
        )))
            ->data(['data' => @json_encode($this->logistic), 'type' => 'delivery_logistic']);
    }

    private function statusMessage(): string
    {
        return 'Logistik anda akan disalurkan ke '. @$this->logistic->delivery->disaster->title;
    }
}
