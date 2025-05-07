<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'dob',
        'phone_number',
        'qualifications',
        'certifications',
        'experience',
        'specialities',
        'bio',
        'location',
        'cases_operated',
        'social_links',
        'availability',
        'consultation_fee',
        'surgery_fee',
        'success_rate',
    ];

    protected $casts = [
        'qualifications' => 'array',
        'certifications' => 'array',
        'experience' => 'array',
        'specialities' => 'array',
        'social_links' => 'array',
        'availability' => 'array',
        'consultation_fee' => 'array',
        'surgery_fee' => 'array',
        'dob' => 'date',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

}
