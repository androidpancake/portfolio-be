<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Projects;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skills extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level'
    ];

    public function Projects()
    {
        return $this->belongsToMany(Projects::class, 'projects_skills', 'skills_id', 'projects_id');
    }
}
