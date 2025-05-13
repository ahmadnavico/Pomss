<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'message',
        'request_approved',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
