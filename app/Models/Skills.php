<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Projects;

class Skills extends Model
{
    public function Projects()
    {
        return $this->belongsToMany(Projects::class);
    }
}
