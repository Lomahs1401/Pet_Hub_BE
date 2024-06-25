<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
// use NotificationChannels\Expo\ExpoMessage;
use YieldStudio\LaravelExpoNotifier\Dto\ExpoMessage;
use YieldStudio\LaravelExpoNotifier\ExpoNotificationsChannel;

class WelcomeNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct()
  {
    //
  }

  // /**
  //  * Get the expo representation of the notification.
  //  */
  // public function toExpo($notifiable): ExpoMessage
  // {
  //   return ExpoMessage::create('Suspicious Activity')
  //     ->body('Someone tried logging in to your account!')
  //     ->data($notifiable->only('email', 'id'))
  //     ->expiresAt(now()->addHour())
  //     ->priority('high')
  //     ->playSound();
  // }

  /**
   * Get the notification's delivery channels.
   */
  public function via($notifiable): array
  {
    return [ExpoNotificationsChannel::class];
  }

  public function toExpoNotification($notifiable): ExpoMessage
  {
    return (new ExpoMessage())
      ->to($notifiable->expoTokens->pluck('value')->toArray())
      ->title('A beautiful title')
      ->body('This is a content')
      ->channelId('default');
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
      //
    ];
  }
}
