<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'Assigned By' => 'required|string|max:255',
            'Assigned To' => 'required|string|max:255',
            'Priority' => 'required|string|max:255',
            'Status' => 'required|string|max:255',
            'Due Date' => 'required|date',
        ];
    }
}
