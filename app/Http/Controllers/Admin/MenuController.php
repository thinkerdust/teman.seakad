<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Models\Menu;
use App\Models\Permission;

class MenuController extends Controller
{
    /**
     * Tampilkan daftar menu (dengan struktur parent-child).
     */
    public function index()
    {
        $menus = Menu::whereNull('parent_id')
            ->orderBy('order')
            ->with(['children' => function ($q) {
                $q->orderBy('order');
            }])
            ->get();

        $parentOptions = Menu::whereNull('parent_id')
            ->orderBy('order')
            ->get();

        $permissions = Permission::orderBy('key')->get();

        return view('admin.menus.index', compact('menus', 'parentOptions', 'permissions'));
    }

    /**
     * Simpan menu baru ke database.
     */
    public function store(StoreMenuRequest $request)
    {
        Menu::create($request->validated());

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu baru berhasil dibuat.');
    }

    /**
     * Update data menu di database.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());

        return redirect()->route('admin.menus.index')
            ->with('success', 'Data menu berhasil diperbarui.');
    }

    /**
     * Hapus menu dari database.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
