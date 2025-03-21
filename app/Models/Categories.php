<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Projects;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function projects()
    {
        return $this->hasMany(Projects::class, 'category_id', 'id');
    }
}
