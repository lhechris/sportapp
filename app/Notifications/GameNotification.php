<?php

namespace App\Notifications;

use App\Models\Game;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;
use \Carbon\Carbon;

class GameNotification extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(public Game $game)
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
        \Log::info("GameNotification::toWebPush");
        return (new WebPushMessage)
            ->title('ASLB')
            ->body('Match le '.Carbon::parse($this->game->date)->translatedFormat('d F Y à H:i').' merci de valider les disponibilités')
            ->icon('/icons/icon-192.png')
            ->data(['url' => route('game.show', ['game' => $this->game->id])]);
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
