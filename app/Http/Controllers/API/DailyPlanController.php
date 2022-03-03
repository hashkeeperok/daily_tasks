<?php

namespace App\Http\Controllers\API;

use App\Models\DailyPlan;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyPlanController extends Controller
{

    public function getOrCreate(Request $request): DailyPlan|JsonResponse {
        $user = $request->user();

        if (!$user) {
            return $this->forbidden();
        }

        $dailyPlan = $user->todayDailyPlan() ?: DailyPlan::createDailyPlan($user->id);

        return $dailyPlan->load('tasks');
    }

    public function taskComplete(Request $request, int $dailyPlanId, int $taskId) {

        /** @var DailyPlan $dailyPlan */
        $dailyPlan = DailyPlan::find($dailyPlanId);

        $task = $dailyPlan->tasks()->find($taskId);

        if (!$task) {
            return $this->forbidden();
        }

        $dailyPlan->taskComplete($task);

        return $dailyPlan->load('tasks');
    }

    public function taskChange(Request $request, int $dailyPlanId, int $taskId) {

        /** @var DailyPlan $dailyPlan */
        $dailyPlan = DailyPlan::find($dailyPlanId);

        $task = $dailyPlan->tasks()->find($taskId);

        if (!$task) {
            return response()->json(['error' => 'task not found.'], 404);
        }

        $dailyPlan->taskChange($task);

        return $dailyPlan->load('tasks');
    }

}
