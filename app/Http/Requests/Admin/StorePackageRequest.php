<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'invitation_quota' => ['required', 'integer', 'min:1'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama paket wajib diisi.',
            'price.required' => 'Harga paket wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'invitation_quota.required' => 'Kuota undangan wajib diisi.',
            'invitation_quota.integer' => 'Kuota undangan harus berupa angka.',
            'invitation_quota.min' => 'Kuota undangan minimal 1.',
            'duration_days.required' => 'Durasi (hari) wajib diisi.',
            'duration_days.integer' => 'Durasi harus berupa angka.',
            'duration_days.min' => 'Durasi minimal 1 hari.',
            'status.required' => 'Status paket wajib dipilih.',
            'status.in' => 'Status paket tidak valid.',
        ];
    }
}
