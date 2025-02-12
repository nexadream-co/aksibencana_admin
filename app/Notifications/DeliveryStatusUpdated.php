<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class DeliveryStatusUpdated extends Notification
{
    use Queueable;

    private Delivery $delivery;

    /**
     * Create a new notification instance.
     */
    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
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
            'title' => 'Status Penyaluran Logistik',
            'body' => $this->statusMessage($this->delivery->status),
            'data' => $this->delivery,
            'type' => 'delivery'
        ];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: 'Status Penyaluran Logistik',
            body: $this->statusMessage($this->delivery->status),
        )))
            ->data(['data' => @json_encode($this->delivery), 'type' => 'delivery']);
    }

    private function statusMessage($status): string
    {
        if ($status == 'finished') {
            return 'Logistik anda telah selesai disalurkan ke '. @$this->delivery->disaster->title;
        } else {
            return 'Mohon maaf, terjadi kesalahan penyaluran logistik.';
        }
    }
}
