<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoMessage;
// use YieldStudio\LaravelExpoNotifier\Dto\ExpoMessage;
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

  /**
   * Get the notification's delivery channels.
   */
  public function via($notifiable): array
  {
    return ['expo'];
  }

  public function toExpo($notifiable): ExpoMessage
  {
    return ExpoMessage::create('Suspicious Activity')
      ->body('Someone tried logging in to your account!')
      ->data($notifiable->only('email', 'id'))
      ->expiresAt(now()->addHour())
      ->priority('high')
      ->playSound();
  }

  // public function toExpoNotification($notifiable): ?ExpoMessage
  // {
  //   $expoTokens = $notifiable->expoTokens->pluck('expo_token')->toArray();

  //   if (empty($expoTokens)) {
  //     // Handle case where there are no expo tokens available for the notifiable
  //     // For example, return a default message or log an error.
  //     // This is just an example, adjust as per your application's logic.
  //     return (new ExpoMessage())
  //       ->to(['mN5oPEJnC3R13CiGr1YOQh'])  // Provide a fallback or default expo token
  //       ->title('Default Title')
  //       ->body('No expo tokens available for this user')
  //       ->channelId('default');
  //   }

  //   return (new ExpoMessage())
  //     ->to($expoTokens)
  //     ->title('A beautiful title')
  //     ->body('This is a content')
  //     ->channelId('default');
  // }

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
