<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'created_at'  => $this->created_at,

            // only show when deleted (deleted_at != null)
            // why we put this line is when the client needs visibility of soft-deleted state (Admin dashboards, audit logs, data recovery such as restore)
            // 'deleted_at' => $this->when($this->deleted_at, $this->deleted_at),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'version' => '1.0',
            'status'  => $request->isMethod('post') ? 201 : 200,
            'message' => $request->isMethod('post') ? 'Project created successfully' : 'Success',
        ];
    }   
}