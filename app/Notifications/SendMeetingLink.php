<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendMeetingLink extends Notification implements ShouldQueue
{
    use Queueable;

    public $post;
    public $name;

    public function __construct(Post $post, $name)
    {
        $this->post = $post;
        $this->name = $name;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Meeting Link for Event: ' . $this->post->title)
            ->greeting("Hi {$this->name},")
            ->line('Thank you for your payment.')
            ->line('Here is your meeting link:')
            ->action('Join Meeting', $this->post->meeting_link)
            ->line('We look forward to seeing you there!');
    }
}
