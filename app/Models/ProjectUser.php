<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectUser extends Model
{
    protected $table = 'project_user';

    protected $fillable = [
        'project_id',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public static function userJobProjects($user_id)
    {
        $jobProjects = DB::table('projects')
            ->select('projects.id', 'projects.name', 'project_user.created_at')
            ->join('project_user','project_user.project_id', '=', 'projects.id')
            ->where('project_user.user_id', $user_id)
            ->get();

        return $jobProjects;
    }

    public static function findOneByUserIdAndProjectId($user_id, $project_id)
    {
        $projectUser = ProjectUser::where('user_id', $user_id)
            ->where('project_id', $project_id)
            ->first();

        return $projectUser;
    }

    public static function findByUserId($user_id)
    {
        $projectUsers = ProjectUser::where('user_id', $user_id)->get();
        return $projectUsers;
    }
}
