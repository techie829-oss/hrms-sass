<?php

namespace App\Modules\HR\Requests;
use App\Core\Constants\PermissionConstants;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(PermissionConstants::MANAGE_DEPARTMENTS);
    }

    public function rules(): array
    {
        $id = $this->route('department');
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->where('tenant_id', saas_tenant('id'))->ignore($id)],
            'code' => ['required', 'string', 'max:20', Rule::unique('departments')->where('tenant_id', saas_tenant('id'))->ignore($id)],
            'description' => ['nullable', 'string'],
        ];
    }
}
