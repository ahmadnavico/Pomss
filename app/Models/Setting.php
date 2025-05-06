<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    // Define the table name if different from the plural form of the model name
    protected $table = 'settings'; 

    // Define which attributes can be mass-assigned
    protected $fillable = [
        'key',
        'value',
    ];
}
