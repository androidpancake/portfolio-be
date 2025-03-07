<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    protected $table = 'project_detail';

    protected $fillable = [
        'projects_id',
        'background',
        'stack1',
        'stack1',
        'stack1',
        'db',
        'logo'
    ];

    public function projects()
    {
        return $this->belongsTo(Projects::class);
    }
}
