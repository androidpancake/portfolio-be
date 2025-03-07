<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class Projects extends Model
{
    use HasUuids, HasFactory, Searchable;
    // Searchable

    protected $fillable = ['title', 'slug', 'description', 'image', 'url', 'category_id', 'start_date', 'end_date', 'created_at'];

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug
        ];
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skills::class, 'projects_skills', 'projects_id', 'skills_id', 'id', 'id');
    }

    public function detailProject()
    {
        return $this->hasOne(ProjectDetail::class);
    }
}
