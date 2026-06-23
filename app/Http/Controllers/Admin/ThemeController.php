<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreThemeRequest;
use App\Http\Requests\Admin\UpdateThemeRequest;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    /**
     * Tampilkan daftar tema dengan pencarian dan filter status.
     */
    public function index(Request $request)
    {
        $query = Theme::query();

        // Pencarian berdasarkan nama, slug, atau deskripsi
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $themes = $query->orderBy('name')->paginate(10)->withQueryString();

        // Deteksi folder template Vue 3 secara dinamis
        $templatesPath = resource_path('js/invitation/templates');
        $availableFolders = [];
        if (is_dir($templatesPath)) {
            $folders = scandir($templatesPath);
            foreach ($folders as $folder) {
                if ($folder !== '.' && $folder !== '..' && is_dir($templatesPath . '/' . $folder)) {
                    $availableFolders[] = $folder;
                }
            }
        }

        // Jika request via AJAX untuk hot-swapping
        if ($request->ajax()) {
            return view('admin.themes.index', compact('themes', 'availableFolders'));
        }

        return view('admin.themes.index', compact('themes', 'availableFolders'));
    }

    /**
     * Simpan tema baru ke database.
     */
    public function store(StoreThemeRequest $request)
    {
        $data = $request->validated();

        // Upload berkas thumbnail jika ada
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('themes/thumbnails', 'public');
            $data['thumbnail'] = '/storage/' . $path;
        }

        Theme::create($data);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Tema baru berhasil ditambahkan.');
    }

    /**
     * Perbarui tema di database.
     */
    public function update(UpdateThemeRequest $request, Theme $theme)
    {
        $data = $request->validated();

        // Upload berkas thumbnail baru jika ada
        if ($request->hasFile('thumbnail')) {
            // Hapus berkas thumbnail lama jika ada
            if ($theme->thumbnail) {
                $oldPath = str_replace('/storage/', '', $theme->thumbnail);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('thumbnail')->store('themes/thumbnails', 'public');
            $data['thumbnail'] = '/storage/' . $path;
        }

        $theme->update($data);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Data tema berhasil diperbarui.');
    }

    /**
     * Hapus tema dari database beserta berkas fisiknya.
     */
    public function destroy(Theme $theme)
    {
        $this->authorizeThemeDelete();

        // Hapus berkas thumbnail fisik jika ada
        if ($theme->thumbnail) {
            $oldPath = str_replace('/storage/', '', $theme->thumbnail);
            Storage::disk('public')->delete($oldPath);
        }

        $theme->delete();

        return redirect()->route('admin.themes.index')
            ->with('success', 'Tema berhasil dihapus dari sistem.');
    }

    /**
     * Proteksi manual izin penghapusan (karena hanya Superadmin yang boleh menghapus).
     */
    protected function authorizeThemeDelete()
    {
        $user = auth()->user();
        if (!$user->hasPermission('theme.delete')) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus tema.');
        }
    }
}
