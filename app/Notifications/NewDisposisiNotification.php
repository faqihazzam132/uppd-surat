<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Disposisi;

class NewDisposisiNotification extends Notification
{
    use Queueable;

    public $disposisi;

    /**
     * Create a new notification instance.
     */
    public function __construct(Disposisi $disposisi)
    {
        $this->disposisi = $disposisi;
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
            'title' => 'Disposisi Masuk Baru',
            'message' => 'Anda menerima disposisi baru dari ' . $this->disposisi->pengirim->name,
            'link' => route('disposisi.show', $this->disposisi->id),
            'no_registrasi' => $this->disposisi->suratMasuk->no_surat, // Mapping no_surat to fit layout
        ];
    }
}
