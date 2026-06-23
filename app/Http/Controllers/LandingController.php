<?php
 
namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Invitation;
use Illuminate\Http\Request;

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

         return view('landing.home', compact('themes', 'featuredInvitation'));
     }
}
