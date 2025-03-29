<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'          => 'required|string|max:255',
            'priority'      => 'nullable|integer',
            'project_id'    => 'nullable|exists:projects,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'Task name is required',
            'name.max'          => 'Task name cannot exceed 255 characters',
            'project_id.exists' => 'The selected project does not exist'
        ];
    }
}
