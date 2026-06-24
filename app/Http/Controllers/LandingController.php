<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Theme;

class LandingController extends Controller
{
    /**
     * Tampilkan halaman utama landing page (public).
     */
    public function index()
    {
        // Ambil tema aktif maksimal 6, diurutkan terbaru
        $themes = Theme::where('status', 'active')
            ->latest()
            ->limit(6)
            ->get();

        // Ambil contoh undangan yang sudah diterbitkan (published) untuk preview
        $featuredInvitation = Invitation::with(['theme', 'galleries', 'events'])
            ->where('status', 'published')
            ->latest()
            ->first();

        // Generate WhatsApp order URL dari konfigurasi
        $whatsappNumber = config('services.whatsapp.admin_number', '6281234567890');
        $whatsappMessage = config('services.whatsapp.order_message', '');
        $whatsappOrderUrl = 'https://wa.me/'.$whatsappNumber.'?text='.urlencode($whatsappMessage);

        // URL WhatsApp tanpa pesan (untuk link kontak biasa)
        $whatsappContactUrl = 'https://wa.me/'.$whatsappNumber;

        return view('landing.home', compact(
            'themes',
            'featuredInvitation',
            'whatsappOrderUrl',
            'whatsappContactUrl'
        ));
    }
}
