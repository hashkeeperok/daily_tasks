<?php

namespace App\Http\Controllers\API;

use App\Models\DailyPlan;
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


}
