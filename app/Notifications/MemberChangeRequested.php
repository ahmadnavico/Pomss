<?php

namespace App\Notifications;

use App\Models\MemberChangeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberChangeRequested extends Notification
{
    use Queueable;

    public $changeRequest;

    public function __construct(MemberChangeRequest $changeRequest)
    {
        $this->changeRequest = $changeRequest;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "{$this->changeRequest->member->user->full_name} change request has been submitted.",
            'member_id' => $this->changeRequest->member_id,
            'change_request_id' => $this->changeRequest->id,
            'url' => route('member-management.edit', ['user' => $this->changeRequest->member->user->id]),
            'submitted_at' => now(),
        ];
    }
}
