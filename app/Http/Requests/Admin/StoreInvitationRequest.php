<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermission('invitation.create');
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     */
    public function rules(): array
    {
        return [
            'theme_id' => ['required', 'exists:themes,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:invitations,slug'],
            'groom_name' => ['required', 'string', 'max:255'],
            'bride_name' => ['required', 'string', 'max:255'],
            'akad_date' => ['nullable', 'date'],
            'reception_date' => ['nullable', 'date'],
            'venue' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'maps_url' => ['nullable', 'url'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Pesan validasi kustom dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'theme_id.required' => 'Tema undangan wajib dipilih.',
            'theme_id.exists' => 'Tema undangan yang dipilih tidak valid.',
            'title.required' => 'Judul undangan wajib diisi.',
            'title.string' => 'Judul undangan harus berupa teks.',
            'title.max' => 'Judul undangan tidak boleh lebih dari 255 karakter.',
            'slug.required' => 'Slug URL wajib diisi.',
            'slug.string' => 'Slug URL harus berupa teks.',
            'slug.max' => 'Slug URL tidak boleh lebih dari 255 karakter.',
            'slug.unique' => 'Slug URL ini sudah digunakan oleh undangan lain.',
            'groom_name.required' => 'Nama mempelai pria wajib diisi.',
            'groom_name.string' => 'Nama mempelai pria harus berupa teks.',
            'groom_name.max' => 'Nama mempelai pria tidak boleh lebih dari 255 karakter.',
            'bride_name.required' => 'Nama mempelai wanita wajib diisi.',
            'bride_name.string' => 'Nama mempelai wanita harus berupa teks.',
            'bride_name.max' => 'Nama mempelai wanita tidak boleh lebih dari 255 karakter.',
            'akad_date.date' => 'Format tanggal akad nikah tidak valid.',
            'reception_date.date' => 'Format tanggal resepsi tidak valid.',
            'venue.required' => 'Tempat acara wajib diisi.',
            'venue.string' => 'Tempat acara harus berupa teks.',
            'venue.max' => 'Tempat acara tidak boleh lebih dari 255 karakter.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'maps_url.url' => 'Format tautan Google Maps tidak valid.',
        ];
    }
}
