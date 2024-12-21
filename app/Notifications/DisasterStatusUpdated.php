<?php

namespace App\Notifications;

use App\Models\Disaster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class DisasterStatusUpdated extends Notification
{
    use Queueable;

    private Disaster $disaster;

    /**
     * Create a new notification instance.
     */
    public function __construct(Disaster $disaster)
    {
        $this->disaster = $disaster;
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
            'title' => 'Status Bencana',
            'body' => $this->statusMessage($this->disaster->status),
            'data' => $this->disaster,
            'type' => 'disaster'
        ];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: 'Status Bencana',
            body: $this->statusMessage($this->disaster->status),
        )))
            ->data(['data' => @json_encode($this->disaster), 'type' => 'disaster']);
    }

    private function statusMessage($status): string
    {
        if ($status == 'active') {
            return 'Selamat, pengajuan bencana anda telah disetujui, dan telah diaktifkan.';
        } else if ($status == 'rejected') {
            return 'Mohon maaf, pengajuan bencana anda tidak disetujui, silahkan hubungi admin Aksi Bencana.';
        } else {
            return 'Mohon maaf, terjadi kesalahan dalam pengajuan bencana.';
        }
    }
}
