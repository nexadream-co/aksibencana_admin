<?php

namespace App\Notifications;

use App\Models\DonationHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class DonationPaymentStatusUpdated extends Notification
{
    // use Queueable;
    private DonationHistory $donation_history;

    /**
     * Create a new notification instance.
     */
    public function __construct(DonationHistory $donation_history)
    {
        $this->donation_history = $donation_history;
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
            'title' => 'Donasi',
            'body' => $this->statusMessage($this->donation_history->status),
            'data' => $this->donation_history,
            'type' => 'donation_history'
        ];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: 'Donasi',
            body: $this->statusMessage($this->donation_history->status),
        )))
            ->data(['data' => $this->donation_history, 'type' => 'donation_history']);
    }

    private function statusMessage($status): string
    {
        if ($status == 'paid') {
            return 'Berhasil, pembayaran donasi anda berhasil dilakukan.';
        } else {
            return 'Mohon maaf, terjadi kesalahan dalam pembayaran donasi.';
        }
    }
}
