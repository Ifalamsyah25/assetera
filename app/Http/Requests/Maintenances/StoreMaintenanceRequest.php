<?php

namespace App\Http\Requests\Maintenances;

use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true);
    }

    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'exists:assets,id'],
            'asset_name_snapshot' => ['nullable', 'string', 'max:255'],
            'category_snapshot' => ['nullable', 'string', 'max:255'],
            'location_snapshot' => ['nullable', 'string', 'max:255'],
            'checkin_date' => ['nullable', 'date'],
            'current_condition' => ['nullable', 'string', 'max:255'],
            'estimated_completion_date' => ['nullable', 'date', 'after_or_equal:checkin_date'],
            'repair_description' => ['required', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(Maintenance::statuses())],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cost' => $this->input('cost') ?: 0,
        ]);
    }
}
