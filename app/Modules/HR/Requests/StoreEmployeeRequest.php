<?php

namespace App\Modules\HR\Requests;

use App\Modules\HR\DTOs\EmployeeData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We can add permission checks here later (e.g. $this->user()->can('create_employees'))
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'string', 'max:20', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('employees')->where('tenant_id', saas_tenant('id'))],
            'personal_email' => ['nullable', 'email'],
            'country_code' => ['nullable', 'string', 'max:10'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'date_of_birth' => ['nullable', 'date'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'aadhar_number' => ['nullable', 'string', 'max:20'],
            'passport_number' => ['nullable', 'string', 'max:20'],
            'current_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:255'],
            'reporting_to' => ['nullable', 'exists:employees,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'date_of_joining' => ['required', 'date'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'intern'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave', 'terminated', 'resigned'])],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'checkin_required' => ['nullable', 'string', 'in:0,1,'],
            'create_login' => ['nullable', 'boolean'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'login_password' => ['nullable', 'string', 'min:8'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ];
    }

    public function toDTO(): EmployeeData
    {
        return EmployeeData::fromArray($this->validated());
    }

    public function getLoginData(): array
    {
        return [
            'create_login' => $this->boolean('create_login'),
            'role_id' => $this->input('role_id'),
            'login_password' => $this->input('login_password'),
            'permissions' => $this->input('permissions', []),
        ];
    }
}
