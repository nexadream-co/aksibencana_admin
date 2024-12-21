<?php

namespace App\Notifications;

use App\Models\Volunteer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class VolunteerStatusUpdated extends Notification
{
    // use Queueable;

    private Volunteer $volunteer;

    /**
     * Create a new notification instance.
     */
    public function __construct(Volunteer $volunteer)
    {
        $this->volunteer = $volunteer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [FcmChannel::class];
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
            'title' => 'Status Volunteer',
            'body' => $this->statusMessage($this->volunteer->status),
            'data' => $this->volunteer,
            'type' => 'volunteer'
        ];
    }

    public function toFcm($notifiable): FcmMessage
    {
        // return (new FcmMessage(notification: new FcmNotification(
        //     title: "Test Notifikasi",
        //     body: "Test notifikasi berhasil",
        // )))
        //     ->data(['data' => 'Test notifikasi', 'type' => 'test']);
        return (new FcmMessage(notification: new FcmNotification(
            title: 'Status Volunteer',
            body: $this->statusMessage(@$this->volunteer->status),
        )))
            ->data(['data' => @json_encode(@$this->volunteer), 'type' => 'volunteer']);
    }

    private function statusMessage($status): string
    {
        if ($status == 'active') {
            return 'Selamat, pengajuan volunteer anda telah disetujui.';
        } else if ($status == 'rejected') {
            return 'Mohon maaf, pengajuan anda sebagai volunteer ditolak.';
        } else {
            return '';
        }
    }
}
