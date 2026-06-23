<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi login.
     */
    public function login(LoginRequest $request)
    {
        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.',
            ])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Check if user status is active
        $user = User::where('email', $credentials['email'])->first();
        if ($user && $user->status !== 'active') {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            RateLimiter::clear($throttleKey);

            // Update last login timestamp
            $user = Auth::user();
            $user->update([
                'last_login_at' => now(),
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Proses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Tampilkan form lupa password.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses pengiriman link reset password.
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        $token = Str::random(60);

        // Simpan token di database (password_reset_tokens)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Buat url reset password
        $resetUrl = route('password.reset', ['token' => $token]) . '?email=' . urlencode($email);

        // Kirim email
        try {
            Mail::to($email)->send(new ResetPasswordMail($resetUrl));
        } catch (\Exception $e) {
            // Fallback ke log jika terjadi error pengiriman (atau tampilkan error jika tidak di local)
            logger()->error('Gagal mengirim email reset password ke ' . $email . ': ' . $e->getMessage());
            
            // Simpan log reset link ke laravel.log agar tetap bisa di-test
            logger()->info('Reset Link untuk ' . $email . ': ' . $resetUrl);
        }

        return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan periksa inbox (atau logs jika di local dev).');
    }

    /**
     * Tampilkan form reset password.
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Proses pembaruan password baru.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Validasi ketersediaan token dan kedaluwarsa (misal 60 menit)
        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau telah kedaluwarsa.']);
        }

        $createdAt = \Carbon\Carbon::parse($record->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset password telah kedaluwarsa. Silakan ajukan ulang.']);
        }

        // Update password user
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus token setelah berhasil digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Password Anda berhasil diperbarui. Silakan masuk menggunakan password baru.');
    }
}
