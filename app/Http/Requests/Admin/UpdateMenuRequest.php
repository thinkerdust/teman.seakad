<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
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
        $menuId = $this->route('menu')?->id;

        return [
            'parent_id' => [
                'nullable',
                'exists:menus,id',
                function ($attribute, $value, $fail) use ($menuId) {
                    if ($menuId && $value == $menuId) {
                        $fail('Menu tidak boleh menjadi parent bagi dirinya sendiri.');
                    }
                },
            ],
            'title' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string'],
            'route' => ['nullable', 'string', 'max:255'],
            'permission' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul menu wajib diisi.',
            'title.max' => 'Judul menu maksimal 255 karakter.',
            'parent_id.exists' => 'Parent menu tidak valid.',
            'order.required' => 'Urutan menu wajib diisi.',
            'order.integer' => 'Urutan menu harus berupa angka.',
            'order.min' => 'Urutan menu minimal 0.',
            'status.required' => 'Status menu wajib dipilih.',
            'status.in' => 'Status menu tidak valid.',
        ];
    }
}
