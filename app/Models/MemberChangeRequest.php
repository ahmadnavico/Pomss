<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberChangeRequest extends Model
{
    use HasFactory;
    use SoftDeletes;


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
