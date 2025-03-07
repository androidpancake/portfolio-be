<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Educations extends Model
{
    protected $fillable = [
        'title',
        'location',
        'gpa',
        'start_date',
        'end_date',
        'major',
        'faculty'
    ];
}
