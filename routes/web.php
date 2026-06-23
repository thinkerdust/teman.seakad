<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\InvitationContentController;
use App\Http\Controllers\PublicInvitationController;
use Illuminate\Support\Facades\Route;

// Public Front-end Landing Page (will be Vue in subsequent phases)
Route::get('/', function () {
    return view('welcome');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Authenticated Admin Dashboard Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:dashboard.view');

    // User Management Resource Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:user.view');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:user.create');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:user.delete');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password')->middleware('permission:user.update');

    // Menu Management Resource Routes
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index')->middleware('permission:menu.view');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store')->middleware('permission:menu.create');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update')->middleware('permission:menu.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy')->middleware('permission:menu.delete');

    // Theme Management Resource Routes
    Route::get('/themes', [ThemeController::class, 'index'])->name('themes.index')->middleware('permission:theme.view');
    Route::post('/themes', [ThemeController::class, 'store'])->name('themes.store')->middleware('permission:theme.create');
    Route::put('/themes/{theme}', [ThemeController::class, 'update'])->name('themes.update')->middleware('permission:theme.update');
    Route::delete('/themes/{theme}', [ThemeController::class, 'destroy'])->name('themes.destroy')->middleware('permission:theme.delete');

    // Invitation Management Resource Routes
    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index')->middleware('permission:invitation.view');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store')->middleware('permission:invitation.create');
    Route::put('/invitations/{invitation}', [InvitationController::class, 'update'])->name('invitations.update')->middleware('permission:invitation.update');
    Route::put('/invitations/{invitation}/toggle-status', [InvitationController::class, 'toggleStatus'])->name('invitations.toggle-status')->middleware('permission:invitation.update');
    Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy')->middleware('permission:invitation.delete');

    // Guest Management (Nested under Invitation)
    Route::get('/invitations/{invitation}/guests', [GuestController::class, 'index'])->name('invitations.guests.index')->middleware('permission:invitation.view');
    Route::post('/invitations/{invitation}/guests', [GuestController::class, 'store'])->name('invitations.guests.store')->middleware('permission:invitation.update');
    Route::put('/invitations/{invitation}/guests/{guest}', [GuestController::class, 'update'])->name('invitations.guests.update')->middleware('permission:invitation.update');
    Route::delete('/invitations/{invitation}/guests/{guest}', [GuestController::class, 'destroy'])->name('invitations.guests.destroy')->middleware('permission:invitation.delete');
    Route::get('/invitations/{invitation}/guests/export', [GuestController::class, 'export'])->name('invitations.guests.export')->middleware('permission:invitation.update');
    Route::post('/invitations/{invitation}/guests/import', [GuestController::class, 'import'])->name('invitations.guests.import')->middleware('permission:invitation.update');

    // Invitation Content Management
    Route::get('/invitations/{invitation}/content', [InvitationContentController::class, 'edit'])->name('invitations.content.edit')->middleware('permission:invitation.update');
    Route::post('/invitations/{invitation}/content/gallery', [InvitationContentController::class, 'updateGallery'])->name('invitations.content.gallery')->middleware('permission:invitation.update');
    Route::post('/invitations/{invitation}/content/story', [InvitationContentController::class, 'updateStory'])->name('invitations.content.story')->middleware('permission:invitation.update');
    Route::post('/invitations/{invitation}/content/event', [InvitationContentController::class, 'updateEvent'])->name('invitations.content.event')->middleware('permission:invitation.update');
    Route::post('/invitations/{invitation}/content/music', [InvitationContentController::class, 'updateMusic'])->name('invitations.content.music')->middleware('permission:invitation.update');
});

// Wildcard Public Invitation Route
Route::get('/{slug}', [PublicInvitationController::class, 'show'])->name('public.invitation');
Route::post('/{slug}/rsvp', [PublicInvitationController::class, 'rsvp'])->name('public.invitation.rsvp');
