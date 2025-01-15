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

    public function Categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
