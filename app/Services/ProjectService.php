<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function __construct(
        protected ProjectRepository $repository
    ) {}

    public function list()
    {
        return $this->repository->all();
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
