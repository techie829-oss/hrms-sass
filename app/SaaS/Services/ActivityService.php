<?php

namespace App\SaaS\Services;

use App\SaaS\Logging\Activity;
use Illuminate\Support\Collection;

class ActivityService
{
    /**
     * Get recent activities for the current tenant.
     */
    public function getRecentActivities(int $limit = 5): Collection
    {
        return Activity::with('causer')
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'description' => $activity->description,
                    'subject_name' => $activity->subject ? $activity->subject->name : 'System',
                    'causer_name' => $activity->causer ? $activity->causer->name : 'System',
                    'time_ago' => $activity->created_at->diffForHumans(),
                    'type' => $activity->event,
                ];
            });
    }
}
