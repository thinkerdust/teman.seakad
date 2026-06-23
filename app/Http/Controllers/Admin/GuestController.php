<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    /**
     * Tampilkan daftar tamu undangan (dengan search & filter).
     */
    public function index(Request $request, Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.view');

        $query = Guest::where('invitation_id', $invitation->id);

        // Filter pencarian berdasarkan nama atau telepon
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status kehadiran
        if ($request->filled('attendance')) {
            $query->where('attendance', $request->input('attendance'));
        }

        $guests = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return view('admin.guests.index', compact('invitation', 'guests'));
        }

        return view('admin.guests.index', compact('invitation', 'guests'));
    }

    /**
     * Simpan tamu baru manual ke database.
     */
    public function store(Request $request, Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'attendance' => ['required', 'string', 'in:hadir,tidak_hadir,belum_pasti'],
            'message' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama tamu wajib diisi.',
            'name.max' => 'Nama tamu tidak boleh lebih dari 255 karakter.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            'attendance.required' => 'Status kehadiran wajib dipilih.',
            'attendance.in' => 'Status kehadiran tidak valid.',
        ]);

        $data['invitation_id'] = $invitation->id;

        Guest::create($data);

        return redirect()->route('admin.invitations.guests.index', $invitation->id)
            ->with('success', 'Tamu baru berhasil ditambahkan.');
    }

    /**
     * Perbarui data tamu di database.
     */
    public function update(Request $request, Invitation $invitation, Guest $guest)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        // Pastikan tamu terikat dengan undangan ini
        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'attendance' => ['required', 'string', 'in:hadir,tidak_hadir,belum_pasti'],
            'message' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama tamu wajib diisi.',
            'name.max' => 'Nama tamu tidak boleh lebih dari 255 karakter.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            'attendance.required' => 'Status kehadiran wajib dipilih.',
            'attendance.in' => 'Status kehadiran tidak valid.',
        ]);

        $guest->update($data);

        return redirect()->route('admin.invitations.guests.index', $invitation->id)
            ->with('success', 'Data tamu berhasil diperbarui.');
    }

    /**
     * Hapus tamu dari database.
     */
    public function destroy(Invitation $invitation, Guest $guest)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.delete');

        // Pastikan tamu terikat dengan undangan ini
        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        $guest->delete();

        return redirect()->route('admin.invitations.guests.index', $invitation->id)
            ->with('success', 'Tamu berhasil dihapus dari daftar.');
    }

    /**
     * Ekspor daftar tamu ke file CSV.
     */
    public function export(Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $guests = Guest::where('invitation_id', $invitation->id)->orderBy('name')->get();

        $filename = "daftar-tamu-" . $invitation->slug . "-" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($guests) {
            $file = fopen('php://output', 'w');
            
            // Tulis Header Kolom
            fputcsv($file, ['Nama Tamu', 'Nomor Telepon', 'Kehadiran', 'Pesan / Ucapan', 'Tanggal RSVP']);

            // Tulis Baris Data
            foreach ($guests as $guest) {
                fputcsv($file, [
                    $guest->name,
                    $guest->phone ?: '-',
                    strtoupper(str_replace('_', ' ', $guest->attendance)),
                    $guest->message ?: '-',
                    $guest->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Impor daftar tamu dari berkas CSV.
     */
    public function import(Request $request, Invitation $invitation)
    {
        $this->authorizeInvitationAction($invitation, 'invitation.update');

        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048']
        ], [
            'csv_file.required' => 'File CSV wajib diunggah.',
            'csv_file.mimes' => 'Format file harus berupa CSV atau TXT.',
            'csv_file.max' => 'Ukuran file maksimal adalah 2MB.'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Buka berkas CSV secara native
        if (($handle = fopen($path, 'r')) !== false) {
            // Baca baris pertama sebagai header
            $header = fgetcsv($handle, 1000, ',');
            
            // Loop data baris demi baris
            $rowCount = 0;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (empty($row[0])) {
                    continue; // Skip jika kolom nama kosong
                }

                // Ambil data dari kolom: Nama (row[0]), Telepon (row[1]), Kehadiran (row[2]), Pesan (row[3])
                $name = trim($row[0]);
                $phone = isset($row[1]) ? trim($row[1]) : null;
                $attendance = isset($row[2]) ? strtolower(trim($row[2])) : 'belum_pasti';
                $message = isset($row[3]) ? trim($row[3]) : null;

                // Normalisasi kehadiran
                $attendance = str_replace(' ', '_', $attendance);
                if (!in_array($attendance, ['hadir', 'tidak_hadir', 'belum_pasti'])) {
                    $attendance = 'belum_pasti';
                }

                // Masukkan atau update di database berdasarkan nama tamu di undangan yang sama
                Guest::updateOrCreate(
                    [
                        'invitation_id' => $invitation->id,
                        'name' => $name
                    ],
                    [
                        'phone' => $phone,
                        'attendance' => $attendance,
                        'message' => $message
                    ]
                );
                $rowCount++;
            }
            fclose($handle);

            return redirect()->route('admin.invitations.guests.index', $invitation->id)
                ->with('success', "Berhasil mengimpor {$rowCount} tamu dari file CSV.");
        }

        return redirect()->route('admin.invitations.guests.index', $invitation->id)
            ->with('error', 'Gagal membaca berkas CSV. Pastikan format berkas valid.');
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
