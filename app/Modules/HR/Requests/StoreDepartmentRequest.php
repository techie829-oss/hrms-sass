<?php

namespace App\Modules\HR\Requests;
use App\Core\Constants\PermissionConstants;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(PermissionConstants::MANAGE_DEPARTMENTS);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->where('tenant_id', saas_tenant('id'))],
            'code' => ['required', 'string', 'max:20', Rule::unique('departments')->where('tenant_id', saas_tenant('id'))],
            'description' => ['nullable', 'string'],
        ];
    }
}
