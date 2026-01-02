<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']); // true or false

        $projectId = $this->route('project');
        
        return [
            'name' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'min:5',
                Rule::unique('projects', 'name')->ignore($projectId),
            ],
            'description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'start_date' => [
                $isUpdate ? 'sometimes' : 'required',
                'nullable',
                'date',
            ],

            'end_date' => [
                'sometimes',
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'name.min' => 'Project name must be at least 5 characters.',
            'name.unique' => 'Project name already exists.',
            'start_date.required' => 'Start date is required.',
            'end_date.after_or_equal' => 'End date must be after start date.',
        ];
    }
}