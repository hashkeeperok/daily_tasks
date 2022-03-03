<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DailyPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function tasks()
    {
        return $this
            ->belongsToMany(Task::class, 'daily_plan_task')
            ->withPivot('complete');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public static function createDailyPlan(int $userId): self
    {
        $dailyPlan = new DailyPlan();
        $dailyPlan->user_id = $userId;
        $dailyPlan->save();

        $taskCount = app('config')->get('daily_tasks')['task_count'];
        $tasks = Task::all()->random($taskCount);

        $dailyPlan->tasks()->attach($tasks);

        return $dailyPlan;
    }
}
