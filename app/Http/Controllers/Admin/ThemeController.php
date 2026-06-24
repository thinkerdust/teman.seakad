<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreThemeRequest;
use App\Http\Requests\Admin\UpdateThemeRequest;
use App\Models\Invitation;
use App\Models\Theme;
use App\Services\ThemeService;
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
                if ($folder !== '.' && $folder !== '..' && is_dir($templatesPath.'/'.$folder)) {
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
            $data['thumbnail'] = '/storage/'.$path;
        }

        if ($request->filled('config')) {
            $data['config'] = json_decode($request->input('config'), true);
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
            $data['thumbnail'] = '/storage/'.$path;
        }

        if ($request->filled('config')) {
            $data['config'] = json_decode($request->input('config'), true);
        } else {
            $data['config'] = null;
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
        if (! $user->hasPermission('theme.delete')) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus tema.');
        }
    }

    /**
     * Tampilkan live preview tema dengan data mock/dummy.
     */
    public function preview(string $slug, ThemeService $themeService)
    {
        $theme = Theme::where('slug', $slug)->firstOrFail();

        // Create mock data
        $invitationData = [
            'title' => 'Pernikahan Romantis '.$theme->name,
            'groom_name' => 'Romeo',
            'bride_name' => 'Juliet',
            'akad_date' => now()->addDays(30)->toIso8601String(),
            'reception_date' => now()->addDays(30)->addHours(2)->toIso8601String(),
            'venue' => 'Katedral Cinta',
            'address' => 'Jl. Asmara No. 14, Verona',
            'maps_url' => 'https://maps.google.com',
            'description' => 'Preview tema '.$theme->name.' dengan data dummy.',
            'recipient_name' => 'Tamu Undangan Terhormat',
            'theme' => [
                'name' => $theme->name,
                'slug' => $theme->slug,
                'folder' => $theme->folder,
            ],
            'gallery' => [
                ['id' => 1, 'image' => '/assets/demo/gallery/IMG_8305.jpg', 'sort' => 1],
                ['id' => 2, 'image' => '/assets/demo/gallery/IMG_8306.jpg', 'sort' => 2],
                ['id' => 3, 'image' => '/assets/demo/gallery/IMG_8309.jpg', 'sort' => 3],
                ['id' => 4, 'image' => '/assets/demo/gallery/IMG_8312.jpg', 'sort' => 4],
                ['id' => 5, 'image' => '/assets/demo/gallery/IMG_8313.jpg', 'sort' => 5],
                ['id' => 6, 'image' => '/assets/demo/gallery/IMG_8314.jpg', 'sort' => 6],
            ],
            'story' => [
                ['id' => 1, 'title' => 'Pertama Bertemu', 'date' => '2020', 'description' => 'Pertemuan pertama kami di kedai kopi hangat.', 'sort' => 1],
                ['id' => 2, 'title' => 'Menjalin Kasih', 'date' => '2022', 'description' => 'Mulai berkomitmen untuk saling menjaga.', 'sort' => 2],
            ],
            'events' => [
                [
                    'id' => 1,
                    'name' => 'Akad Nikah',
                    'date' => now()->addDays(30)->format('Y-m-d'),
                    'time' => '08:00 - 10:00',
                    'location' => 'Katedral Cinta, Aula Utama',
                ],
                [
                    'id' => 2,
                    'name' => 'Resepsi Pernikahan',
                    'date' => now()->addDays(30)->format('Y-m-d'),
                    'time' => '11:00 - selesai',
                    'location' => 'Katedral Cinta, Glass House',
                ],
            ],
            'music' => [
                'title' => 'Lagu Pernikahan Demo',
                'artist' => 'Demo Artist',
                'file' => '/assets/demo/music/lagu-nikah.mp3',
            ],
        ];

        // Create a mock invitation instance
        $invitation = new Invitation;
        $invitation->slug = $theme->slug;
        $invitation->title = $invitationData['title'];
        $invitation->groom_name = $invitationData['groom_name'];
        $invitation->bride_name = $invitationData['bride_name'];
        $invitation->akad_date = now()->addDays(30);
        $invitation->reception_date = now()->addDays(30)->addHours(2);
        $invitation->venue = $invitationData['venue'];
        $invitation->address = $invitationData['address'];
        $invitation->maps_url = $invitationData['maps_url'];
        $invitation->theme = $theme;

        // Custom relation mocking so Blade doesn't crash
        $invitation->setRelation('guests', collect());
        $invitation->setRelation('galleries', collect());
        $invitation->setRelation('stories', collect());
        $invitation->setRelation('events', collect());
        $invitation->setRelation('music', collect());

        $view = $themeService->getThemeView($theme);
        $themeConfig = $themeService->getThemeConfig($theme);

        return view($view, compact('invitation', 'invitationData', 'themeConfig'));
    }
}
