<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\Music;
use App\Models\Story;
use App\Services\MusicRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvitationContentController extends Controller
{
    /**
     * Tampilkan formulir edit konten undangan (sistem Tab).
     */
    public function edit(Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $invitation->load(['galleries', 'stories', 'events', 'music', 'guests']);

        $musicLibrary = Music::where('status', 'active')->orderBy('title')->get();
        $recommendationService = new MusicRecommendationService;
        $recommendedMusic = $recommendationService->recommend($invitation);

        return view('admin.invitations.content.edit', compact('invitation', 'musicLibrary', 'recommendedMusic'));
    }

    /**
     * Perbarui data mempelai (Couple).
     */
    public function updateCouple(Request $request, Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $data = $request->validate([
            'groom_name' => ['required', 'string', 'max:255'],
            'bride_name' => ['required', 'string', 'max:255'],
            'groom_nickname' => ['nullable', 'string', 'max:255'],
            'bride_nickname' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'groom_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'bride_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'groom_name.required' => 'Nama lengkap mempelai pria wajib diisi.',
            'bride_name.required' => 'Nama lengkap mempelai wanita wajib diisi.',
            'title.required' => 'Judul undangan wajib diisi.',
            'groom_photo.image' => 'Foto mempelai pria harus berupa gambar.',
            'groom_photo.max' => 'Ukuran foto mempelai pria maksimal adalah 2MB.',
            'bride_photo.image' => 'Foto mempelai wanita harus berupa gambar.',
            'bride_photo.max' => 'Ukuran foto mempelai wanita maksimal adalah 2MB.',
        ]);

        // Upload foto pria
        if ($request->hasFile('groom_photo')) {
            if ($invitation->groom_photo) {
                $oldPath = str_replace('/storage/', '', $invitation->groom_photo);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('groom_photo')->store('invitations/avatars', 'public');
            $data['groom_photo'] = '/storage/' . $path;
        }

        // Upload foto wanita
        if ($request->hasFile('bride_photo')) {
            if ($invitation->bride_photo) {
                $oldPath = str_replace('/storage/', '', $invitation->bride_photo);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('bride_photo')->store('invitations/avatars', 'public');
            $data['bride_photo'] = '/storage/' . $path;
        }

        $invitation->update($data);

        return redirect()->back()->with('success', 'Data mempelai berhasil diperbarui.');
    }

    /**
     * Perbarui gaya visual (Style Customization).
     */
    public function updateStyle(Request $request, Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $request->validate([
            'primary_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'font_scale' => ['required', 'numeric', 'min:0.5', 'max:2.0'],
            'background_option' => ['required', 'string', 'in:texture,plain'],
        ], [
            'primary_color.regex' => 'Format warna utama tidak valid (harus berupa kode HEX, contoh: #ff0000).',
            'secondary_color.regex' => 'Format warna sekunder tidak valid.',
            'font_scale.required' => 'Skala ukuran font wajib diisi.',
            'font_scale.numeric' => 'Skala ukuran font harus berupa angka.',
            'font_scale.min' => 'Skala font minimal 0.5.',
            'font_scale.max' => 'Skala font maksimal 2.0.',
            'background_option.required' => 'Opsi latar belakang wajib dipilih.',
            'background_option.in' => 'Opsi latar belakang tidak valid.',
        ]);

        $customization = $invitation->customization ?: [];
        $customization['custom_style'] = [
            'primary_color' => $request->input('primary_color'),
            'secondary_color' => $request->input('secondary_color'),
            'font_scale' => $request->input('font_scale'),
            'background_option' => $request->input('background_option'),
        ];

        $invitation->update([
            'customization' => $customization,
        ]);

        return redirect()->back()->with('success', 'Kustomisasi gaya visual berhasil diperbarui.');
    }

    /**
     * Kelola Tab Galeri Foto (Unggah & Hapus).
     */
    public function updateGallery(Request $request, Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $action = $request->input('action', 'upload');

        if ($action === 'upload') {
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:20480'],
            ], [
                'image.required' => 'File gambar wajib diunggah.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'image.max' => 'Ukuran gambar maksimal adalah 20MB.',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('invitations/gallery', 'public');

                Gallery::create([
                    'invitation_id' => $invitation->id,
                    'image' => '/storage/'.$path,
                    'sort' => $invitation->galleries()->count(),
                ]);

                return redirect()->back()->with('success', 'Foto berhasil ditambahkan ke galeri.');
            }
        } elseif ($action === 'delete') {
            $request->validate([
                'gallery_id' => ['required', 'exists:galleries,id'],
            ]);

            $gallery = Gallery::findOrFail($request->gallery_id);

            // Pastikan gambar terikat dengan undangan ini
            if ($gallery->invitation_id !== $invitation->id) {
                abort(403);
            }

            // Hapus file fisik
            $oldPath = str_replace('/storage/', '', $gallery->image);
            Storage::disk('public')->delete($oldPath);

            // Hapus record database
            $gallery->delete();

            return redirect()->back()->with('success', 'Foto berhasil dihapus dari galeri.');
        } elseif ($action === 'toggle-visibility') {
            $request->validate([
                'gallery_id' => ['required', 'exists:galleries,id'],
            ]);

            $gallery = Gallery::findOrFail($request->gallery_id);

            if ($gallery->invitation_id !== $invitation->id) {
                abort(403);
            }

            $gallery->update([
                'is_visible' => !$gallery->is_visible,
            ]);

            return redirect()->back()->with('success', 'Visibilitas foto berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Tindakan galeri tidak valid.');
    }

    /**
     * Kelola Tab Cerita Cinta (Simpan List Milestones).
     */
    public function updateStory(Request $request, Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $request->validate([
            'stories' => ['nullable', 'array'],
            'stories.*.id' => ['nullable', 'exists:stories,id'],
            'stories.*.title' => ['required', 'string', 'max:255'],
            'stories.*.date' => ['required', 'string', 'max:255'],
            'stories.*.description' => ['required', 'string'],
            'stories.*.image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'delete_story_ids' => ['nullable', 'string'], // IDs dipisah koma (contoh: "1,2,3")
        ], [
            'stories.*.title.required' => 'Judul cerita wajib diisi.',
            'stories.*.date.required' => 'Tanggal/Waktu cerita wajib diisi.',
            'stories.*.description.required' => 'Isi cerita wajib diisi.',
            'stories.*.image.image' => 'File cerita harus berupa gambar.',
            'stories.*.image.max' => 'Ukuran gambar cerita maksimal adalah 2MB.',
        ]);

        // 1. Hapus cerita yang ditandai untuk dihapus
        if ($request->filled('delete_story_ids')) {
            $deleteIds = explode(',', $request->delete_story_ids);
            
            // Delete associated images first
            $storiesToDelete = Story::whereIn('id', $deleteIds)->where('invitation_id', $invitation->id)->get();
            foreach ($storiesToDelete as $s) {
                if ($s->image) {
                    $oldPath = str_replace('/storage/', '', $s->image);
                    Storage::disk('public')->delete($oldPath);
                }
                $s->delete();
            }
        }

        // 2. Simpan atau perbarui milestones cerita
        if ($request->has('stories')) {
            foreach ($request->stories as $index => $storyData) {
                $storyPayload = [
                    'title' => $storyData['title'],
                    'date' => $storyData['date'],
                    'description' => $storyData['description'],
                    'sort' => $index,
                ];

                if ($request->hasFile("stories.{$index}.image")) {
                    // Hapus gambar lama jika mengupdate
                    if (!empty($storyData['id'])) {
                        $oldStory = Story::find($storyData['id']);
                        if ($oldStory && $oldStory->image) {
                            $oldPath = str_replace('/storage/', '', $oldStory->image);
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                    $path = $request->file("stories.{$index}.image")->store('invitations/stories', 'public');
                    $storyPayload['image'] = '/storage/' . $path;
                }

                Story::updateOrCreate(
                    [
                        'id' => $storyData['id'] ?? null,
                        'invitation_id' => $invitation->id,
                    ],
                    $storyPayload
                );
            }
        }

        return redirect()->back()->with('success', 'Cerita cinta berhasil diperbarui.');
    }

    /**
     * Kelola Tab Susunan Acara (Simpan List Events).
     */
    public function updateEvent(Request $request, Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $request->validate([
            'events' => ['nullable', 'array'],
            'events.*.id' => ['nullable', 'exists:events,id'],
            'events.*.name' => ['required', 'string', 'max:255'],
            'events.*.date' => ['required', 'date'],
            'events.*.time' => ['required', 'string', 'max:255'],
            'events.*.location' => ['required', 'string'],
            'delete_event_ids' => ['nullable', 'string'],
        ], [
            'events.*.name.required' => 'Nama acara wajib diisi.',
            'events.*.date.required' => 'Tanggal acara wajib diisi.',
            'events.*.time.required' => 'Waktu/Jam acara wajib diisi.',
            'events.*.location.required' => 'Lokasi/Tempat acara wajib diisi.',
        ]);

        // 1. Hapus acara yang ditandai untuk dihapus
        if ($request->filled('delete_event_ids')) {
            $deleteIds = explode(',', $request->delete_event_ids);
            Event::whereIn('id', $deleteIds)
                ->where('invitation_id', $invitation->id)
                ->delete();
        }

        // 2. Simpan atau perbarui detail acara
        if ($request->has('events')) {
            foreach ($request->events as $eventData) {
                Event::updateOrCreate(
                    [
                        'id' => $eventData['id'] ?? null,
                        'invitation_id' => $invitation->id,
                    ],
                    [
                        'name' => $eventData['name'],
                        'date' => $eventData['date'],
                        'time' => $eventData['time'],
                        'location' => $eventData['location'],
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Susunan acara berhasil diperbarui.');
    }

    /**
     * Kelola Tab Musik Latar (Unggah & Hapus MP3/WAV).
     */
    public function updateMusic(Request $request, Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $action = $request->input('action', 'select');

        if ($action === 'select') {
            $request->validate([
                'music_id' => ['required', 'exists:music,id'],
                'wedding_mood' => ['nullable', 'string', 'max:255'],
            ], [
                'music_id.required' => 'Lagu wajib dipilih.',
                'music_id.exists' => 'Lagu terpilih tidak valid.',
            ]);

            // Clean up old custom music if exists
            $oldMusic = $invitation->music()->first();
            if ($oldMusic && $oldMusic->status === 'custom' && $oldMusic->id != $request->input('music_id')) {
                $invitation->music()->detach();
                $oldPath = str_replace('/storage/', '', $oldMusic->file);
                Storage::disk('public')->delete($oldPath);
                $oldMusic->delete();
            }

            // Simpan mood pernikahan ke undangan
            $invitation->update([
                'wedding_mood' => $request->input('wedding_mood'),
            ]);

            // Hubungkan musik dengan undangan (Many-to-Many sync)
            $invitation->music()->sync([$request->input('music_id')]);

            return redirect()->back()->with('success', 'Musik latar berhasil diperbarui.');
        } elseif ($action === 'upload') {
            $request->validate([
                'music_file' => ['required', 'file', 'mimes:mp3,wav', 'max:20480'],
                'title' => ['nullable', 'string', 'max:255'],
            ], [
                'music_file.required' => 'File musik wajib diunggah.',
                'music_file.mimes' => 'Format musik harus berupa MP3 atau WAV.',
                'music_file.max' => 'Ukuran file musik maksimal adalah 20MB.',
            ]);

            // Clean up old custom music if exists
            $oldMusic = $invitation->music()->first();
            if ($oldMusic) {
                $invitation->music()->detach();
                if ($oldMusic->status === 'custom') {
                    $oldPath = str_replace('/storage/', '', $oldMusic->file);
                    Storage::disk('public')->delete($oldPath);
                    $oldMusic->delete();
                }
            }

            if ($request->hasFile('music_file')) {
                $file = $request->file('music_file');
                $filename = $file->getClientOriginalName();
                $title = $request->input('title') ?: pathinfo($filename, PATHINFO_FILENAME);

                $path = $file->store('invitations/music', 'public');

                $customMusic = Music::create([
                    'title' => $title,
                    'artist' => 'Custom Upload',
                    'genre' => 'Wedding',
                    'mood' => 'Romantic',
                    'file' => '/storage/'.$path,
                    'status' => 'custom',
                ]);

                $invitation->music()->sync([$customMusic->id]);

                return redirect()->back()->with('success', 'Musik latar kustom berhasil diunggah.');
            }
        } elseif ($action === 'delete') {
            // Clean up old custom music if exists
            $oldMusic = $invitation->music()->first();
            if ($oldMusic) {
                $invitation->music()->detach();
                if ($oldMusic->status === 'custom') {
                    $oldPath = str_replace('/storage/', '', $oldMusic->file);
                    Storage::disk('public')->delete($oldPath);
                    $oldMusic->delete();
                }
            }

            return redirect()->back()->with('success', 'Musik latar berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tindakan musik tidak valid.');
    }

    /**
     * Proteksi manual hak akses tindakan undangan.
     */
    protected function authorizeInvitationAction(Invitation $invitation, string $permission)
    {
        $user = Auth::user();

        if (! $user->hasPermission($permission)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan tindakan ini.');
        }

        // Superadmin & Admin dapat melakukan tindakan ke semua undangan
        if ($user->hasRole('Superadmin') || $user->hasRole('Admin') || $user->email === 'admin@teman-seakad.com') {
            return;
        }

        // Regular user hanya bisa mengubah undangan miliknya sendiri
        if ($invitation->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola undangan ini.');
        }
    }
}
