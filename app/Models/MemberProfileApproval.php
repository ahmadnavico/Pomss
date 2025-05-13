<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberProfileApproval extends Model
{
    protected $fillable = ['member_id', 'is_approved', 'message'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
