<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $fillable = [
        'title',
        'url',
        'order',
        'is_display'
    ];
}
