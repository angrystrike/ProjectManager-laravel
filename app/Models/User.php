<?php

namespace App\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable;
    use Messagable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'middle_name',
        'last_name',
        'city',
        'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function companies() {
        return $this->hasMany(Company::class);
    }

    public function tasks() {
        return $this->belongsToMany(Task::class);
    }

    public function projects() {
        return $this->belongsToMany(Project::class);
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
