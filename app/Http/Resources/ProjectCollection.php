<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($project) {
            return [
                'id'          => $project->id,
                'name'        => $project->name,
                'description' => $project->description,
                'start_date'  => $project->start_date,
                'end_date'    => $project->end_date,
            ];
        });
    }

    public function with($request)
    {
        return [
            'version' => '1.0',
            'status'  => 200,
            'message' => 'Projects retrieved successfully',
        ];
    }
}