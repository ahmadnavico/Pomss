<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MemberProfileApprovalNotification extends Notification
{
    use Queueable;

    public $isApproved;
    public $message;
    public $member;

    public function __construct($member, $isApproved, $message = null)
    {
        $this->member = $member;
        $this->isApproved = $isApproved;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // Only store in DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->isApproved
                ? 'Your profile has been approved.'
                : 'Your profile has been rejected.',
            'reason' => $this->message,
            'member_id' => $this->member->id,
            'user_id' => $this->member->user->id,
            'url' => route('member-management.edit', ['user' => $this->member->user->id]),
        ];
    }
}
