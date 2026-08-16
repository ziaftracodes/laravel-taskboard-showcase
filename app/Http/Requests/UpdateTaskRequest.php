<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'status' => ['required', 'in:todo,in_progress,completed'],
            'due_date' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'A clear task title is required.',
            'category_id.exists' => 'The selected project/category is invalid.',
            'priority.in' => 'Priority must be one of: low, medium, high, or urgent.',
            'status.in' => 'Status must be one of: todo, in_progress, or completed.',
        ];
    }
}
