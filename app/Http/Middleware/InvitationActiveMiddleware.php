<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Invitation;

class InvitationActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');
        $invitation = Invitation::where('slug', $slug)->first();

        // 1. Jika tidak ditemukan, abort 404
        if (!$invitation) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Undangan tidak ditemukan.'
                ], 404);
            }
            abort(404, 'Undangan tidak ditemukan.');
        }

        // 2. Jika status adalah draft
        if ($invitation->status === 'draft') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Undangan ini masih dalam status draft dan belum diterbitkan oleh pemilik.'
                ], 403);
            }
            abort(403, 'Undangan ini masih dalam status draft dan belum diterbitkan oleh pemilik.');
        }

        // 3. Pengecekan kedaluwarsa (expired)
        $isExpired = false;
        if ($invitation->status === 'expired') {
            $isExpired = true;
        } elseif ($invitation->expired_at && $invitation->expired_at->isPast()) {
            $isExpired = true;
        }

        if ($isExpired) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Undangan sudah tidak tersedia.'
                ], 410);
            }
            return response()->view('public.expired', [], 410);
        }

        // Simpan instance invitation di atribut request untuk efisiensi
        $request->attributes->set('invitation', $invitation);

        return $next($request);
    }
}
