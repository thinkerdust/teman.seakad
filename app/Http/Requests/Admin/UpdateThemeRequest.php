<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateThemeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermission('theme.update');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $themeId = $this->route('theme') ? $this->route('theme')->id : '';

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:themes,slug,'.$themeId],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'description' => ['nullable', 'string'],
            'folder' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'view_path' => ['nullable', 'string', 'max:255'],
            'config' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama tema wajib diisi.',
            'slug.required' => 'Slug tema wajib diisi.',
            'slug.unique' => 'Slug tema ini sudah digunakan.',
            'folder.required' => 'Folder tema wajib diisi.',
            'status.required' => 'Status tema wajib dipilih.',
            'status.in' => 'Status tema tidak valid.',
            'thumbnail.image' => 'File sampul harus berupa gambar.',
            'thumbnail.mimes' => 'Format sampul harus jpeg, png, jpg, atau gif.',
            'thumbnail.max' => 'Ukuran sampul maksimal 2MB.',
        ];
    }
}
