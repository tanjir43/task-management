<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'priority',
        'project_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
