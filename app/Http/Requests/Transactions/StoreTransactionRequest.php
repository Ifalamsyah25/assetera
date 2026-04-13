<?php

namespace App\Http\Requests\Transactions;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'asset_id' => ['required', 'exists:assets,id'],
            'borrowed_at' => ['required', 'date'],
            'returned_at' => ['nullable', 'date', 'after_or_equal:borrowed_at'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cost' => $this->input('cost') ?: 0,
        ]);
    }
}
