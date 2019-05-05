<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'kind',
        'priority',
        'days',
        'project_id',
        'user_id',
        'company_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
