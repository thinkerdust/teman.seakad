<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvitationResource;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvitationApiController extends Controller
{
    /**
     * Tampilkan detail undangan publik berdasarkan slug.
     */
    public function show(Request $request, string $slug): InvitationResource|JsonResponse
    {
        // Cari undangan beserta tema terkait dan konten pelengkapnya
        $invitation = Invitation::with(['theme', 'galleries', 'stories', 'events', 'music'])
            ->where('slug', $slug)
            ->first();

        // 1. Jika tidak ditemukan, return response 404
        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Undangan tidak ditemukan.'
            ], 404);
        }

        // 2. Jika status adalah draft
        if ($invitation->status === 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Undangan ini masih dalam status draft.'
            ], 403);
        }

        // 3. Jika status kedaluwarsa (expired secara eksplisit atau melewati expired_at)
        $isExpired = false;
        if ($invitation->status === 'expired') {
            $isExpired = true;
        } elseif ($invitation->expired_at && $invitation->expired_at->isPast()) {
            $isExpired = true;
        }

        if ($isExpired) {
            return response()->json([
                'success' => false,
                'message' => 'Undangan ini sudah tidak aktif / melewati masa kedaluwarsa.'
            ], 410);
        }

        // Return resources detail undangan
        return new InvitationResource($invitation);
    }

    /**
     * Dapatkan rekomendasi lagu berdasarkan tema undangan.
     */
    public function recommend(Request $request, string $theme): JsonResponse
    {
        $moods = [];
        switch (strtolower($theme)) {
            case 'floral-elegant':
            case 'floral':
                $moods = ['Romantic'];
                break;
            case 'luxury-gold':
            case 'luxury':
                $moods = ['Elegant', 'Luxury'];
                break;
            case 'islamic-wedding':
            case 'islamic':
                $moods = ['Islamic'];
                break;
            case 'rustic-forest':
            case 'rustic':
                $moods = ['Acoustic', 'Classic'];
                break;
            default:
                $moods = ['Romantic', 'Elegant'];
                break;
        }

        $tracks = \App\Models\Music::whereIn('mood', $moods)
            ->where('status', 'active')
            ->get()
            ->map(function ($track) {
                return [
                    'id' => $track->id,
                    'title' => $track->title,
                    'artist' => $track->artist,
                    'cover' => $track->cover ? asset($track->cover) : null,
                    'preview' => $track->file ? asset($track->file) : '',
                ];
            });

        return response()->json([
            'theme' => $theme,
            'recommendations' => $tracks,
        ]);
    }
}
