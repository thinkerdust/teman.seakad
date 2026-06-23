<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMusicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermission('music.update');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'artist' => ['required', 'string', 'max:255'],
            'album' => ['nullable', 'string', 'max:255'],
            'genre' => ['required', 'string', 'max:255'],
            'mood' => ['required', 'string', 'in:Romantic,Elegant,Luxury,Islamic,Classic,Modern,Acoustic,Instrumental'],
            'language' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:50'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'preview_url' => ['nullable', 'url', 'max:500'],
            'music_file' => ['nullable', 'file', 'mimes:mp3,wav', 'max:20480'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul lagu wajib diisi.',
            'artist.required' => 'Nama artis wajib diisi.',
            'genre.required' => 'Kategori genre wajib diisi.',
            'mood.required' => 'Mood lagu wajib dipilih.',
            'mood.in' => 'Mood lagu tidak valid.',
            'cover.image' => 'Sampul lagu harus berupa gambar.',
            'cover.mimes' => 'Format sampul harus jpeg, png, jpg, atau gif.',
            'cover.max' => 'Ukuran sampul maksimal 2MB.',
            'preview_url.url' => 'Tautan pratinjau harus berupa URL yang valid.',
            'music_file.mimes' => 'Format musik harus berupa MP3 atau WAV.',
            'music_file.max' => 'Ukuran file musik maksimal adalah 20MB.',
            'status.required' => 'Status musik wajib dipilih.',
            'status.in' => 'Status musik tidak valid.',
        ];
    }
}
