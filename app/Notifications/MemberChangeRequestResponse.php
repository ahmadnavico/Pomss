<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class MemberChangeRequestResponse extends Notification
{
    use Queueable;
    public $member;
    public $approved;
    public $reason;

    public function __construct($member, bool $approved, ?string $reason = null)
    {
        $this->member = $member;
        $this->approved = $approved;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->approved ? 'Change Request Approved' : 'Change Request Rejected',
            'message' => $this->approved
                ? 'Your profile change request has been approved.'
                : 'Your request has been rejected.',
            'reason' => $this->approved ? null : $this->reason,
            'member_id' => $this->member->id,
        ];
    }
}