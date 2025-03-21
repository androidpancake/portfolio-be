<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;

class Projects extends Model
{
    use HasUuids, HasFactory, SoftDeletes;
    // Searchable

    protected $fillable = ['title', 'slug', 'description', 'image', 'url', 'category_id', 'start_date', 'end_date', 'status', 'scheduled_at'];

    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

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
        return $this->belongsToMany(Skills::class, 'projects_skills', 'projects_id', 'skills_id');
    }

    public function detailProject()
    {
        return $this->hasOne(ProjectDetail::class, 'project_id');
    }
}
