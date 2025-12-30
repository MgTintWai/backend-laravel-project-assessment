<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use App\Traits\Cachable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    // Traits
    use Cachable;

    // caching
    protected string $cachedPagesKey = 'project_cached_pages';

    public function __construct(
        protected ProjectRepository $repository
    ) {}

    public function list(int $perPage = 20)
    {
        $page = request('page', 1);
        $cacheKey = "projects_page_{$perPage}_{$page}";

        $this->addCachedPage($cacheKey);

        return $this->cacheRemember($cacheKey, function () use ($perPage) {
            return $this->repository->findAllPaginated($perPage);
        });
    }
    /**
     * Track cached pages for invalidation
     */
    protected function addCachedPage(string $cacheKey): void
    {
        $cachedPages = Cache::get($this->cachedPagesKey, []);
        if (!in_array($cacheKey, $cachedPages)) {
            $cachedPages[] = $cacheKey;
            Cache::forever($this->cachedPagesKey, $cachedPages);
        }
    }
    public function getCachedPages(): array
    {
        return Cache::get($this->cachedPagesKey, []);
    }
    public function clearCachedPages(): void
    {
        Cache::forget($this->cachedPagesKey);
    }

    public function find(int $id)
    {
        return $this->repository->findById($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->repository->update($id, $data);
        });
    }

    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->repository->delete($id);
        });
    }

    public function restore(int $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->repository->restore($id);
        });
    }

    public function forceDelete(int $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->repository->forceDelete($id);
        });
    }
}
