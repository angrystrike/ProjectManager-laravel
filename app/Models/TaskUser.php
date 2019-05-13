<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskUser extends Model
{
    protected $table = 'task_user';

    protected $fillable = [
        'task_id',
        'user_id',
    ];

    public static function userJobTasks($user_id)
    {
        $jobTasks = DB::table('tasks')
            ->select('tasks.id', 'tasks.name', 'task_user.created_at')
            ->join('task_user','task_user.task_id', '=', 'tasks.id')
            ->where('task_user.user_id', $user_id)
            ->get();

        return $jobTasks;
    }

    public static function findOneByUserIdAndTaskId($user_id, $task_id)
    {
        $taskUser = TaskUser::where('user_id', $user_id)
            ->where('task_id', $task_id)
            ->first();

        return $taskUser;
    }

    public static function findByTaskId($task_id)
    {
        $tasks = TaskUser::where('task_id', $task_id)->get();
        return $tasks;
    }

    public static function findByUserId($user_id)
    {
        $tasks = TaskUser::where('user_id', $user_id)->get();
        return $tasks;
    }
}
