<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceMarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:present,absent,late'],
            'date' => ['nullable', 'date'],
            'student_id' => ['nullable', 'exists:users,id'],
            'class_id' => ['nullable', 'exists:classes,id'],
        ];
    }
}