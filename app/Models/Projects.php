<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Projects extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = ['title', 'description', 'image', 'url', 'category_id', 'start_date', 'end_date'];

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skills::class, 'projects_skills', 'projects_id', 'skills_id', 'id', 'id');
    }
}
