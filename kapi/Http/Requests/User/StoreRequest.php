<?php

namespace Kapi\Http\Requests\User;

use Kapi\Models\JobTitle;
use Illuminate\Support\Str;
use Kapi\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
            'email' => ['bail', 'required', 'string', 'email', 'max:100', 'unique:users,email'],
            'job_title_id' => ['nullable', 'exists:job_titles,id'],
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'dob' => ['nullable', 'date'],
            'landline' => ['nullable', 'string', 'min:2', 'max:100'],
            'mobile' => ['nullable', 'string', 'min:2', 'max:100'],
        ];
    }

    /**
     * Custom message for validation
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required!',
            'first_name.required' => 'First Name is required!',
            'last_name.required' => 'Last Name is required!',
            'password.required' => 'Password is required!',
            'email.unique' => 'The :attribute ' . $this->email . ' has already been taken.',
        ];
    }

    /**
     * Prepare the data for sanitise or validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // if job title id is missing and job title is given, we will try to map
        if ($this->job_title_id == '' && $this->job_title != '') {
            if ($jobTitle = JobTitle::firstWhere('job_title', $this->job_title)) {
                $this->job_title_id = $jobTitle->id;
            }
        }

        $this->merge([
            'email' => strtolower(trim(filter_var($this->email, FILTER_SANITIZE_EMAIL))),
            'job_title_id' => (int) $this->job_title_id,
            'first_name' => ucfirst(trim(Str::ucfirst($this->first_name))),
            'last_name' => ucfirst(trim(Str::ucfirst($this->last_name))),
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * passedValidation
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        $this->replace(['password' => Hash::make($this->password)]);
    }
}
