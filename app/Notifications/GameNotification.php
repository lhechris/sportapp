<?php

namespace App\Notifications;

//use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class GameNotification extends Notification
{
    //use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        \Log::info("GameNotification::__construct");
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        \Log::info("GameNotification::via");
        return [WebPushChannel::class];
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

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        \Log::info("GameNotification::via");
        return (new WebPushMessage)
            ->title('ASLB')
            ->body('Nouveau match programmé ce samedi !')
            ->icon('/icons/icon-192.png')
            ->data(['url' => route('game.show',['game' => 3])]);
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
