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
        'bio',
        'location',
        'social_links',
        'availability',
    ];

    protected $casts = [
        'dob' => 'date',
        'certifications' => 'array',
        'experience' => 'array',
        'specialities' => 'array',
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
    public function qualifications()
    {
        return $this->belongsToMany(Qualification::class, 'member_qualification', 'member_id', 'qualification_id');
    }

}
