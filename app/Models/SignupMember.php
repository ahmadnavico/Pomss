<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SignupMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'name',
        'email',
        'payment_method',
    ];

    /**
     * A signup member has one payment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * A signup belongs to a post (event).
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
