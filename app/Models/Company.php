<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function projects() {
        return $this->hasMany(Project::class);
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public static function findByUserId($user_id)
    {
        $companies = Company::where('user_id', $user_id)->get();
        return $companies;
    }

    public static function createOne($name, $description, $user_id)
    {
        $company = Company::create([
            'name' => $name,
            'description' => $description,
            'user_id' => $user_id
        ]);

        return $company;
    }
}
