<?php

/**
 * Planning
 *
 * - Observes the `VersionControl` model lifecycle events.
 * - Triggers cache invalidation when:
 *   - A record is created/updated (`saved`).
 *   - A record is deleted (`deleted`).
 * - Calls the `ProjectService` to fetch and clear cached pages list.
 * - Purpose:
 *   - Ensure data consistency by clearing outdated cache entries whenever model changes.
 *   - Prevent stale cache issues in paginated API responses.
 */

namespace App\Observers;

use App\Models\Project;
// use Illuminate\Support\Facades\Cache;

class ProjectObserver
{
    public function saved(Project $project)
    {
        $this->flushProjectCache();
    }

    public function deleted(Project $project)
    {
        $this->flushProjectCache();
    }

    public function restored(Project $project)
    {
        $this->flushProjectCache();
    }

    public function forceDeleted(Project $project)
    {
        $this->flushProjectCache();
    }

    protected function flushProjectCache(): void
    {
        $service = app(\App\Services\ProjectService::class);
        $service->flushCacheTag();
    }
}

// class ProjectObserver
// {
//     public function saved(Project $project)
//     {
//         $this->clearCachedPages();
//     }

//     public function deleted(Project $project)
//     {
//         $this->clearCachedPages();
//     }

//     protected function clearCachedPages()
//     {
//         $service = app(\App\Services\ProjectService::class);

//         foreach ($service->getCachedPages() as $cacheKey) {
//             Cache::forget($cacheKey);
//         }

//         $service->clearCachedPages();
//     }
// }