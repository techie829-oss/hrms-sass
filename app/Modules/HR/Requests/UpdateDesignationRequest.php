<?php

namespace App\Modules\HR\Requests;
use App\Core\Constants\PermissionConstants;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(PermissionConstants::MANAGE_DEPARTMENTS);
    }

    public function rules(): array
    {
        $id = $this->route('designation');
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('designations')->where('tenant_id', saas_tenant('id'))->ignore($id)],
            'department_id' => ['required', 'exists:departments,id'],
            'description' => ['nullable', 'string'],
            'min_salary' => ['nullable', 'numeric', 'min:0'],
            'max_salary' => ['nullable', 'numeric', 'min:0', 'gte:min_salary'],
            'is_active' => ['boolean'],
        ];
    }
}
