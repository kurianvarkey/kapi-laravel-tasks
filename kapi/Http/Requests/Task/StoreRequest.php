<?php

namespace Kapi\Http\Requests\Task;

use Kapi\Http\Requests\BaseRequest;

final class StoreRequest extends BaseRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'numeric', 'not_in:0'],
            'title' => ['required', 'string', 'min:2', 'max:250'],
            'description' => ['required', 'string', 'min:2', 'max:1000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'is_completed' => ['nullable', 'boolean'],
            'completed_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Custom message for validation
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required!',
            'description.required' => 'Description is required!',
            'start_date.required' => 'Start date is required and should be a valid date time. e.g: 2023-01-01 10:00',
            'end_date.required' => 'End date is required and should be a valid date time. e.g: 2023-01-01 10:30',
            'start_date.date' => 'Start date should be a valid date time. e.g: 2023-01-01 10:00',
            'end_date.date' => 'End date should be a valid date time. e.g: 2023-01-01 10:30',
            'completed_at.date' => 'Completed date should be a valid date time. e.g: 2023-01-01 10:30',
        ];
    }

    /**
     * Prepare the data for sanitise or validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => (int) request()->user_id ?? 0,       
        ]);
    }    
}
