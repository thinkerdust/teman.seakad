<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\InvitationVisit;
use App\Services\ThemeService;
use App\Services\ThemeConfigService;
use Illuminate\Http\Request;

class PublicInvitationController extends Controller
{
    /**
     * Tampilkan undangan publik berdasarkan slug.
     */
    public function show(Request $request, string $slug, ThemeService $themeService, ThemeConfigService $themeConfigService, \App\Services\InvitationCustomizationService $customizationService)
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

        // Resolve view based on theme engine
        $view = 'public.invitation';
        $themeConfig = [];
        $themeCssTokens = '';

        if ($invitation->theme) {
            $view = $themeService->getThemeView($invitation->theme);
            $baseConfig = $themeConfigService->load($invitation->theme);

            // Merge customization & prepare data
            $customized = $customizationService->getCustomizedData($invitation, $baseConfig);
            $themeConfig = $customized['themeConfig'];
            $invitationData = $customized['invitationData'];

            $themeCssTokens = $themeService->getThemeCssTokens($themeConfig);
            view()->share('themeConfig', $themeConfig);
        } else {
            $customized = $customizationService->getCustomizedData($invitation, []);
            $invitationData = $customized['invitationData'];
        }

        return view($view, compact('invitation', 'invitationData', 'themeConfig', 'themeCssTokens'));
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
