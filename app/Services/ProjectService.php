<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use App\Traits\Cachable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
class ProjectService
{
    // Traits
    use Cachable;

    // Tag name for projects
    protected const CACHE_TAG = 'projects';

    public function __construct(
        protected ProjectRepository $repository
    ) {}

    public function list(int $perPage = 20): LengthAwarePaginator
    {
        $page = request('page', 1);
        $cacheKey = "projects_page_{$perPage}_{$page}";

        return $this->cacheRememberWithTags(
            $cacheKey,
            fn () => $this->repository->findAllPaginated($perPage),
            self::CACHE_TAG
        );
    }
    /**
     * Cache remember with tags (O(1) invalidation)
     */
    protected function cacheRememberWithTags(string $key, callable $callback, string $tag, int $ttl = 3600)
    {
        return Cache::tags([$tag])->remember($key, $ttl, $callback);
    }

    /**
     * Flush all caches for this tag
     */
    public function flushCacheTag(): void
    {
        Cache::tags([self::CACHE_TAG])->flush();
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
