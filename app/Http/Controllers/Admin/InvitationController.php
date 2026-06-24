<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInvitationRequest;
use App\Http\Requests\Admin\UpdateInvitationRequest;
use App\Models\Invitation;
use App\Models\Theme;
use App\Services\SubscriptionService;
use App\Services\QuotaService;
use App\Services\InvitationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    protected SubscriptionService $subscriptionService;
    protected QuotaService $quotaService;
    protected InvitationService $invitationService;

    public function __construct(
        SubscriptionService $subscriptionService,
        QuotaService $quotaService,
        InvitationService $invitationService
    ) {
        $this->subscriptionService = $subscriptionService;
        $this->quotaService = $quotaService;
        $this->invitationService = $invitationService;
    }
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

        $hasActiveSubscription = false;
        $remainingQuota = 0;
        $totalQuota = 0;
        $createdCount = 0;
        $isRegularUser = $user->email !== 'admin@teman-seakad.com' && !$user->hasRole('Superadmin') && !$user->hasRole('Admin');

        if ($isRegularUser) {
            $hasActiveSubscription = $this->subscriptionService->checkActive($user);

            if ($hasActiveSubscription) {
                $today = \Carbon\Carbon::today();
                $activeSub = $user->subscriptions()
                    ->where('status', 'active')
                    ->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today)
                    ->first();

                $totalQuota = $activeSub->order ? $activeSub->order->quota : 0;
                $createdCount = $user->invitations()->count();
                $remainingQuota = max(0, $totalQuota - $createdCount);
            }
        }

        $viewData = compact(
            'invitations', 
            'themes',
            'isRegularUser',
            'hasActiveSubscription',
            'totalQuota',
            'createdCount',
            'remainingQuota'
        );

        // Jika request via AJAX untuk hot-swapping
        if ($request->ajax()) {
            return view('admin.invitations.index', $viewData);
        }

        return view('admin.invitations.index', $viewData);
    }

    /**
     * Simpan undangan baru ke database.
     */
    public function store(StoreInvitationRequest $request)
    {
        $user = Auth::user();
        $isRegularUser = $user->email !== 'admin@teman-seakad.com' && !$user->hasRole('Superadmin') && !$user->hasRole('Admin');

        if ($isRegularUser) {
            if (!$this->subscriptionService->checkActive($user)) {
                return redirect()->route('admin.invitations.index')
                    ->with('error', 'Masa aktif akun Anda sudah berakhir, silakan melakukan perpanjangan.');
            }

            if (!$this->quotaService->consumeQuota($user)) {
                return redirect()->route('admin.invitations.index')
                    ->with('error', 'Kuota pembuatan undangan Anda sudah habis. Silakan lakukan perpanjangan atau hubungi admin.');
            }
        }

        $data = $request->validated();
        $data['user_id'] = $user->id;
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
            ]);
            $message = 'Undangan berhasil dinonaktifkan (kembali ke draft).';
        } else {
            // Ubah menjadi published (Publish)
            $this->invitationService->publishInvitation($invitation);
            
            $invitation->refresh();
            
            if ($invitation->expired_at) {
                $message = 'Undangan berhasil diterbitkan (aktif sampai ' . $invitation->expired_at->translatedFormat('d F Y') . ').';
            } else {
                $message = 'Undangan berhasil diterbitkan.';
            }
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
