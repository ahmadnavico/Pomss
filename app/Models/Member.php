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
        'cnic_copy',
        'pmdc_licence_copy',
        'fcps_degree_copy',
        'certifications',
        'experience',
        'specialities',
        'qualifications',
        'bio',
        'location',
        'social_links',
        'availability',
        'profile_submitted',
    ];

    protected $casts = [
        'dob' => 'date',
        'certifications' => 'array',
        'experience' => 'array',
        'specialities' => 'array',
        'qualifications' => 'array',
        'social_links' => 'array',
        'availability' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }
    public function changeRequest()
    {
        return $this->hasOne(MemberChangeRequest::class);
    }
    

}
