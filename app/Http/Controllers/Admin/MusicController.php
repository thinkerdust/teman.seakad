<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMusicRequest;
use App\Http\Requests\Admin\UpdateMusicRequest;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MusicController extends Controller
{
    /**
     * Tampilkan daftar musik latar dengan pencarian dan filter mood/status.
     */
    public function index(Request $request)
    {
        $this->authorizeMusicAction('music.view');

        $query = Music::query();

        // Pencarian berdasarkan judul, artis, atau album
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('artist', 'like', "%{$search}%")
                  ->orWhere('album', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan mood
        if ($request->filled('mood')) {
            $query->where('mood', $request->input('mood'));
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $musicList = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $moods = ['Romantic', 'Elegant', 'Luxury', 'Islamic', 'Classic', 'Modern', 'Acoustic', 'Instrumental'];

        if ($request->ajax()) {
            return view('admin.music.index', compact('musicList', 'moods'));
        }

        return view('admin.music.index', compact('musicList', 'moods'));
    }

    /**
     * Tampilkan form pembuatan musik baru.
     */
    public function create()
    {
        $this->authorizeMusicAction('music.create');
        $moods = ['Romantic', 'Elegant', 'Luxury', 'Islamic', 'Classic', 'Modern', 'Acoustic', 'Instrumental'];
        return view('admin.music.create', compact('moods'));
    }

    /**
     * Simpan musik latar baru ke database.
     */
    public function store(StoreMusicRequest $request)
    {
        $data = $request->validated();

        // Unggah cover image jika ada
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('music/covers', 'public');
            $data['cover'] = '/storage/' . $path;
        }

        // Unggah berkas audio
        if ($request->hasFile('music_file')) {
            $path = $request->file('music_file')->store('music', 'public');
            $data['file'] = '/storage/' . $path;
        }

        Music::create($data);

        return redirect()->route('admin.music.index')
            ->with('success', 'Musik baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan form pengeditan musik.
     */
    public function edit(Music $music)
    {
        $this->authorizeMusicAction('music.update');
        $moods = ['Romantic', 'Elegant', 'Luxury', 'Islamic', 'Classic', 'Modern', 'Acoustic', 'Instrumental'];
        return view('admin.music.edit', compact('music', 'moods'));
    }

    /**
     * Perbarui data musik latar di database.
     */
    public function update(UpdateMusicRequest $request, Music $music)
    {
        $data = $request->validated();

        // Ganti cover image jika ada
        if ($request->hasFile('cover')) {
            if ($music->cover) {
                $oldPath = str_replace('/storage/', '', $music->cover);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('cover')->store('music/covers', 'public');
            $data['cover'] = '/storage/' . $path;
        }

        // Ganti file musik jika ada
        if ($request->hasFile('music_file')) {
            if ($music->file) {
                $oldPath = str_replace('/storage/', '', $music->file);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('music_file')->store('music', 'public');
            $data['file'] = '/storage/' . $path;
        }

        $music->update($data);

        return redirect()->route('admin.music.index')
            ->with('success', 'Data musik berhasil diperbarui.');
    }

    /**
     * Hapus musik dari database beserta berkas fisiknya.
     */
    public function destroy(Music $music)
    {
        $this->authorizeMusicAction('music.delete');

        // Hapus cover jika ada
        if ($music->cover) {
            $oldPath = str_replace('/storage/', '', $music->cover);
            Storage::disk('public')->delete($oldPath);
        }

        // Hapus file musik jika ada
        if ($music->file) {
            $oldPath = str_replace('/storage/', '', $music->file);
            Storage::disk('public')->delete($oldPath);
        }

        $music->delete();

        return redirect()->route('admin.music.index')
            ->with('success', 'Musik berhasil dihapus dari sistem.');
    }

    /**
     * Proteksi manual hak akses tindakan musik.
     */
    protected function authorizeMusicAction(string $permission)
    {
        $user = auth()->user();
        if (!$user || !$user->hasPermission($permission)) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola perpustakaan musik.');
        }
    }
}
