<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificates extends Model
{
    protected $fillable = [
        'title',
        'organizer',
        'image',
        'file',
        'expired_date',
        'category_id'
    ];
}
