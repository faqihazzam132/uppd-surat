<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusSuratNotification extends Notification
{
    use Queueable;

    public $message;
    public $url;
    public $no_surat;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $url, $no_surat)
    {
        $this->message = $message;
        $this->url = $url;
        $this->no_surat = $no_surat;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Update Status Surat Keluar',
            'message' => $this->message,
            'url' => $this->url,
            'no_registrasi' => $this->no_surat, // Reuse field existing di layout
        ];
    }
}
