<?php

namespace App\Repositories;

use App\Contracts\ProjectInterface;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository extends BaseRepository implements ProjectInterface
{
    public function __construct(Project $project)
    {
        parent::__construct($project);
    }

    public function findByName(string $name): Collection
    {
        return $this->model->where('name', 'LIKE', "%{$name}%")->get();
    }
}