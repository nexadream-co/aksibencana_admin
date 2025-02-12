<?php

namespace App\Notifications;

use App\Models\VolunteerAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class VolunteerAssignmentStatusUpdated extends Notification
{
    
    use Queueable;

    private VolunteerAssignment $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct(VolunteerAssignment $assignment)
    {
        $this->assignment = $assignment;
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
            'title' => 'Terima kasih!',
            'body' => $this->statusMessage($this->assignment->status),
            'data' => $this->assignment,
            'type' => 'assignment_finished'
        ];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: 'Terima kasih!',
            body: $this->statusMessage($this->assignment->status),
        )))
            ->data(['data' => @json_encode($this->assignment), 'type' => 'assignment_finished']);
    }

    private function statusMessage($status): string
    {
        $disaster = @$this->assignment->disaster;
        $station = @$disaster->station;
        return "Selamat anda telah menyelesaikan rangkaian kegiatan relawan bencana $disaster->title di daerah $station->name, sertifikat penghargaan akan dikirim melalui email";
    }
}
