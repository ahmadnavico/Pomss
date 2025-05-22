<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'signup_member_id',
        'card_number',
        'cvc',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'payment_status',
        'amount',
    ];

    /**
     * A payment belongs to one signup member.
     */
    public function signupMember()
    {
        return $this->belongsTo(SignupMember::class);
    }
}
