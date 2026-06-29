<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestRejected extends Notification
{
    use Queueable;

    public $assetRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct($assetRequest)
    {
        $this->assetRequest = $assetRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Aset Ditolak')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Mohon maaf, pengajuan aset untuk barang "' . $this->assetRequest->item_name . '" (Sebanyak ' . $this->assetRequest->quantity . ' unit) telah ditolak oleh pimpinan.')
            ->line('Alasan penolakan: ' . $this->assetRequest->reject_reason)
            ->action('Lihat Detail Pengajuan', url('/asset-requests'))
            ->line('Terima kasih telah menggunakan sistem kami.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
