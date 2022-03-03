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

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = ['id', 'user_id', 'tasks'];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'daily_plan_task')->withPivot('complete');
    }

    public function taskComplete($task)
    {
        $task->pivot->complete = true;
        $task->pivot->save();
    }

    public function taskChange($task)
    {
        $newTask = Task::select('id')
                       ->where('category_id', $task->category_id)
                       ->where('id', '<>', $task->id)
                       ->inRandomOrder()
                       ->limit(1)
                       ->get();

        $this->tasks()->detach($task);
        $this->tasks()->attach($newTask);
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


        $categoryId = Category::all()->random()->id;
        $taskCount = app('config')->get('daily_tasks')['task_count'];
        $tasks = Task::select('id')->where('category_id', $categoryId)->inRandomOrder()->limit($taskCount)->get();

        $dailyPlan->tasks()->attach($tasks);

        return $dailyPlan;
    }
}
