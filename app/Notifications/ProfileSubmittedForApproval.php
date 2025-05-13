<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\User;

class ProfileSubmittedForApproval extends Notification
{
    use Queueable;

    public $member;

    public function __construct($member)
    {
        $this->member = $member;
    }

    public function via($notifiable)
    {
        return ['database']; // only store in DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "{$this->member->user->full_name} has submitted their profile for approval.",
            'member_id' => $this->member->id,
            'user_id' => $this->member->user->id,
            'url' => route('member-management.edit', ['user' => $this->member->user->id]),
        ];
    }
}
