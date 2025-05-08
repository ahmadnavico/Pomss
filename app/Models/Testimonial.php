<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'member_id',
        'patient_name',
        'patient_image',
        'feedback',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
