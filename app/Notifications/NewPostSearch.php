<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewPostSearch extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The description of the notification.
     *
     * @var string
     */
    public $subject;

    /**
     * The url of the notification.
     *
     * @var string
     */
    public $posts;

    /**
     * @var int
     */
    public $count;

    /**
     * Create a new notification instance.
     *
     * @param array $ids
     * @param int $count
     */
    public function __construct(array $ids = [], int $count = 0)
    {
        $this->subject = 'A new post search occurred!';
        $this->posts = $ids;
        $this->count = $count;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            $this->subject,
            $this->posts
        ];
    }
}
