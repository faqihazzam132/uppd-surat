<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanDiterimaNotification extends Notification
{
    use Queueable;

    public $pengajuan;

    /**
     * Create a new notification instance.
     */
    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pengajuan Anda Telah Diterima')
                    ->line('Pengajuan Anda dengan nomor registrasi ' . $this->pengajuan->no_registrasi . ' telah berhasil diterima.')
                    ->line('Jenis Surat: ' . $this->pengajuan->jenis_surat)
                    ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->pengajuan->status)))
                    ->action('Lihat Status Pengajuan', url('/pengajuan/' . $this->pengajuan->id))
                    ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'no_registrasi' => $this->pengajuan->no_registrasi,
            'jenis_surat' => $this->pengajuan->jenis_surat,
            'status' => $this->pengajuan->status,
            'title' => 'Permohonan baru diterima',
            'message' => 'Pengajuan Anda dengan nomor registrasi ' . $this->pengajuan->no_registrasi . ' telah berhasil diterima.',
            'url' => url('/pengajuan/' . $this->pengajuan->id),
        ];
    }
}
