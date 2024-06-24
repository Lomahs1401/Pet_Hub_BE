<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoMessage;

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

  /**
   * Get the notification's delivery channels.
   */
  public function via($notifiable): array
  {
    return ['expo'];
  }

  /**
   * Get the expo representation of the notification.
   */
  public function toExpo($notifiable): ExpoMessage
  {
    return ExpoMessage::create()
      ->title('Welcome!')
      ->body('Thank you for signing up!')
      ->setData(['key' => 'value'])
      ->badge(1)
      ->enableSound();
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
