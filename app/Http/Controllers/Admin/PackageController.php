<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePackageRequest;
use App\Http\Requests\Admin\UpdatePackageRequest;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Tampilkan daftar paket.
     */
    public function index(Request $request)
    {
        $query = Package::query();

        // Search by name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        $packages = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Simpan paket baru ke database.
     */
    public function store(StorePackageRequest $request)
    {
        Package::create($request->validated());

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket baru berhasil dibuat.');
    }

    /**
     * Perbarui data paket di database.
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        $package->update($request->validated());

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    /**
     * Hapus paket dari database.
     */
    public function destroy(Package $package)
    {
        // Hubungan orders akan di-set null otomatis oleh foreign key constraint onDelete('set null')
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}
