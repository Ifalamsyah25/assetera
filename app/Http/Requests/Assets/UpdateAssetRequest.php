<?php

namespace App\Http\Requests\Assets;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true);
    }

    public function rules(): array
    {
        /** @var Asset $asset */
        $asset = $this->route('asset');

        return [
            'code_asset' => ['required', 'string', 'max:255', Rule::unique('assets', 'code_asset')->ignore($asset->id)],
            'name_asset' => ['required', 'string', 'max:255'],
            'category_asset' => ['required', 'string', 'max:255'],
            'merk_asset' => ['nullable', 'string', 'max:255'],
            'lokasi_asset' => ['nullable', 'string', 'max:255'],
            'kondisi_asset' => ['nullable', 'string', 'max:255'],
            'status_asset' => ['required', Rule::in(Asset::statuses())],
            'purchase_date' => ['required', 'date'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'deskripsi_asset' => ['nullable', 'string'],
        ];
    }
}
