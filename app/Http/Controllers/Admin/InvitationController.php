<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInvitationRequest;
use App\Http\Requests\Admin\UpdateInvitationRequest;
use App\Models\Invitation;
use App\Models\Theme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Tampilkan daftar undangan dengan pencarian dan filter status.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Invitation::query()->with(['user', 'theme']);

        // Batasi data jika bukan Superadmin atau Admin
        if (!$user->hasRole('Superadmin') && !$user->hasRole('Admin') && $user->email !== 'admin@teman-seakad.com') {
            $query->where('user_id', $user->id);
        }

        // Pencarian berdasarkan judul, slug, mempelai pria, atau mempelai wanita
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('groom_name', 'like', "%{$search}%")
                  ->orWhere('bride_name', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'expired') {
                $query->where(function ($q) {
                    $q->where('status', 'expired')
                      ->orWhere(function ($sub) {
                          $sub->where('status', 'published')
                              ->whereNotNull('expired_at')
                              ->where('expired_at', '<', Carbon::now());
                      });
                });
            } else {
                $query->where('status', $status);
            }
        }

        $invitations = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        // Ambil tema aktif untuk dropdown formulir pembuatan/pembaruan undangan
        $themes = Theme::where('status', 'active')->orderBy('name')->get();

        // Jika request via AJAX untuk hot-swapping
        if ($request->ajax()) {
            return view('admin.invitations.index', compact('invitations', 'themes'));
        }

        return view('admin.invitations.index', compact('invitations', 'themes'));
    }

    /**
     * Simpan undangan baru ke database.
     */
    public function store(StoreInvitationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'draft';

        Invitation::create($data);

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Undangan baru berhasil dibuat.');
    }

    /**
     * Perbarui data undangan di database.
     */
    public function update(UpdateInvitationRequest $request, Invitation $invitation)
    {
        $data = $request->validated();
        
        $invitation->update($data);

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Data undangan berhasil diperbarui.');
    }

    /**
     * Hapus undangan dari database.
     */
    public function destroy(Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.delete');

        $invitation->delete();

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Undangan berhasil dihapus.');
    }

    /**
     * Toggle status publish/disable undangan.
     */
    public function toggleStatus(Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        if ($invitation->status === 'published') {
            // Ubah menjadi draft (Disable)
            $invitation->update([
                'status' => 'draft',
                'expired_at' => null,
            ]);
            $message = 'Undangan berhasil dinonaktifkan (kembali ke draft).';
        } else {
            // Ubah menjadi published (Publish)
            $invitation->update([
                'status' => 'published',
                'expired_at' => Carbon::now()->addDays(30),
            ]);
            $message = 'Undangan berhasil diterbitkan (aktif selama 30 hari).';
        }

        if (request()->ajax()) {
            // Kita return redirect agar data tabel terupdate via AJAX parsing
            return redirect()->route('admin.invitations.index')->with('success', $message);
        }

        return redirect()->route('admin.invitations.index')->with('success', $message);
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
