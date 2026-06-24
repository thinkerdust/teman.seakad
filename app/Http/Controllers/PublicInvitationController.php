<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\InvitationVisit;
use App\Services\ThemeService;
use Illuminate\Http\Request;

class PublicInvitationController extends Controller
{
    /**
     * Tampilkan undangan publik berdasarkan slug.
     */
    public function show(Request $request, string $slug, ThemeService $themeService)
    {
        // Ambil instance undangan dari request attribute yang telah divalidasi oleh middleware
        $invitation = $request->attributes->get('invitation');

        // Eager load hubungan yang diperlukan untuk merender view
        $invitation->load(['theme', 'galleries', 'stories', 'events', 'music', 'guests']);

        // 4. Catat statistik kunjungan baru ke database
        InvitationVisit::create([
            'invitation_id' => $invitation->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Ambil nama penerima dari query string '?to=Nama+Tamu'
        $recipientName = $request->query('to');

        // Transformasikan data agar bersih dan rapi untuk dibaca oleh Vue/Blade
        $invitationData = [
            'title' => $invitation->title,
            'groom_name' => $invitation->groom_name,
            'bride_name' => $invitation->bride_name,
            'akad_date' => $invitation->akad_date ? $invitation->akad_date->toIso8601String() : null,
            'reception_date' => $invitation->reception_date ? $invitation->reception_date->toIso8601String() : null,
            'venue' => $invitation->venue,
            'address' => $invitation->address,
            'maps_url' => $invitation->maps_url,
            'description' => $invitation->description,
            'recipient_name' => $recipientName,
            'theme' => [
                'name' => $invitation->theme?->name,
                'slug' => $invitation->theme?->slug,
                'folder' => $invitation->theme?->folder,
            ],
            'gallery' => $invitation->galleries->sortBy('sort')->map(function ($g) {
                return [
                    'id' => $g->id,
                    'image' => $g->image,
                    'sort' => $g->sort,
                ];
            })->values()->all(),
            'story' => $invitation->stories->sortBy('sort')->map(function ($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'date' => $s->date,
                    'description' => $s->description,
                    'sort' => $s->sort,
                ];
            })->values()->all(),
            'events' => $invitation->events->sortBy('date')->map(function ($e) {
                return [
                    'id' => $e->id,
                    'name' => $e->name,
                    'date' => $e->date ? $e->date->format('Y-m-d') : null,
                    'time' => $e->time,
                    'location' => $e->location,
                ];
            })->values()->all(),
            'music' => $invitation->music->first() ? [
                'title' => $invitation->music->first()->title,
                'artist' => $invitation->music->first()->artist,
                'file' => $invitation->music->first()->file,
            ] : [
                'title' => '',
                'artist' => '',
                'file' => '',
            ],
        ];

        // Resolve view based on theme engine
        $view = 'public.invitation';
        $themeConfig = [];
        if ($invitation->theme) {
            $view = $themeService->getThemeView($invitation->theme);
            $themeConfig = $themeService->getThemeConfig($invitation->theme);
        }

        return view($view, compact('invitation', 'invitationData', 'themeConfig'));
    }

    /**
     * Kirim konfirmasi kehadiran (RSVP) dari halaman publik.
     */
    public function rsvp(Request $request, string $slug)
    {
        $invitation = $request->attributes->get('invitation');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'attendance' => ['required', 'string', 'in:hadir,tidak_hadir,belum_pasti'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        // Simpan atau update kehadiran tamu
        $guest = Guest::updateOrCreate(
            [
                'invitation_id' => $invitation->id,
                'name' => $data['name'],
            ],
            [
                'phone' => $data['phone'] ?? null,
                'attendance' => $data['attendance'],
                'message' => $data['message'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih, konfirmasi kehadiran Anda berhasil dikirim!',
        ]);
    }
}
