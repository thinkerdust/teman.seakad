<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\Music;
use App\Models\Story;
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

        $invitation->load(['galleries', 'stories', 'events', 'music']);

        return view('admin.invitations.content.edit', compact('invitation'));
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
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:20480']
            ], [
                'image.required' => 'File gambar wajib diunggah.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'image.max' => 'Ukuran gambar maksimal adalah 20MB.'
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('invitations/gallery', 'public');
                
                Gallery::create([
                    'invitation_id' => $invitation->id,
                    'image' => '/storage/' . $path,
                    'sort' => $invitation->galleries()->count(),
                ]);

                return redirect()->back()->with('success', 'Foto berhasil ditambahkan ke galeri.');
            }
        } elseif ($action === 'delete') {
            $request->validate([
                'gallery_id' => ['required', 'exists:galleries,id']
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
            'delete_story_ids' => ['nullable', 'string'], // IDs dipisah koma (contoh: "1,2,3")
        ], [
            'stories.*.title.required' => 'Judul cerita wajib diisi.',
            'stories.*.date.required' => 'Tanggal/Waktu cerita wajib diisi.',
            'stories.*.description.required' => 'Isi cerita wajib diisi.',
        ]);

        // 1. Hapus cerita yang ditandai untuk dihapus
        if ($request->filled('delete_story_ids')) {
            $deleteIds = explode(',', $request->delete_story_ids);
            Story::whereIn('id', $deleteIds)
                ->where('invitation_id', $invitation->id)
                ->delete();
        }

        // 2. Simpan atau perbarui milestones cerita
        if ($request->has('stories')) {
            foreach ($request->stories as $index => $storyData) {
                Story::updateOrCreate(
                    [
                        'id' => $storyData['id'] ?? null,
                        'invitation_id' => $invitation->id
                    ],
                    [
                        'title' => $storyData['title'],
                        'date' => $storyData['date'],
                        'description' => $storyData['description'],
                        'sort' => $index,
                    ]
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
                        'invitation_id' => $invitation->id
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

        $action = $request->input('action', 'upload');

        if ($action === 'upload') {
            $request->validate([
                'music_file' => ['required', 'file', 'mimes:mp3,wav', 'max:20480'] // Maksimal 20MB
            ], [
                'music_file.required' => 'File musik wajib diunggah.',
                'music_file.mimes' => 'Format musik harus berupa MP3 atau WAV.',
                'music_file.max' => 'Ukuran file musik maksimal adalah 20MB.'
            ]);

            if ($request->hasFile('music_file')) {
                // Hapus musik lama jika ada
                if ($invitation->music) {
                    $oldPath = str_replace('/storage/', '', $invitation->music->file);
                    Storage::disk('public')->delete($oldPath);
                    $invitation->music->delete();
                }

                // Simpan berkas baru
                $path = $request->file('music_file')->store('invitations/music', 'public');

                Music::create([
                    'invitation_id' => $invitation->id,
                    'file' => '/storage/' . $path
                ]);

                return redirect()->back()->with('success', 'Musik latar berhasil diperbarui.');
            }
        } elseif ($action === 'delete') {
            if ($invitation->music) {
                $oldPath = str_replace('/storage/', '', $invitation->music->file);
                Storage::disk('public')->delete($oldPath);
                $invitation->music->delete();

                return redirect()->back()->with('success', 'Musik latar berhasil dihapus.');
            }

            return redirect()->back()->with('error', 'Tidak ada musik latar untuk dihapus.');
        }

        return redirect()->back()->with('error', 'Tindakan musik tidak valid.');
    }

    /**
     * Proteksi manual hak akses tindakan undangan.
     */
    protected function authorizeInvitationAction(Invitation $invitation, string $permission)
    {
        $user = Auth::user();
        
        if (!$user->hasPermission($permission)) {
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
