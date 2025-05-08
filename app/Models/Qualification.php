<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    protected $fillable = [
        'name',
    ];
    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_qualification')->withTimestamps();
    }

}
