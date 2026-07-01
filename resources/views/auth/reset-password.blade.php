<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | Teman Seakad Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Assets -->
    <script>
        // Sinkronkan tema gelap dari localStorage sebelum page load untuk menghindari flash
        try {
            const darkMode = localStorage.getItem('_x_darkMode');
            if (darkMode === 'true') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {
            console.error('Gagal memuat tema gelap', e);
        }
    </script>
    @vite(['resources/css/admin.css'])
</head>
<body class="bg-slate-50 font-sans text-slate-600 antialiased dark:bg-slate-950 dark:text-slate-400">

    <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md">
            
            <!-- Brand Logo / Title -->
            <div class="mb-8 text-center">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-none">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m-5-4v1a3 3 0 00-3 3H6a3 3 0 00-3 3 2 2 0 002 2h14a2 2 0 002-2m-4-3a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-800 dark:text-white">
                    Ubah Password Anda
                </h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Silakan masukkan password baru Anda di bawah ini
                </p>
            </div>

            <!-- Card -->
            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                
                <!-- Validation Errors Alert -->
                @if($errors->any())
                    <div class="mb-6">
                        <div class="rounded-lg bg-rose-50 p-4 text-sm font-medium text-rose-800 dark:bg-rose-950/20 dark:text-rose-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <!-- Hidden fields -->
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Display Read-only Email for confirmation -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">
                            Mereset Password untuk Email
                        </label>
                        <div class="mt-2 rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-800 font-semibold border border-slate-100 dark:bg-slate-800/50 dark:border-slate-800/80 dark:text-slate-300">
                            {{ $email }}
                        </div>
                    </div>

                    <!-- New Password Input -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            Password Baru
                        </label>
                        <div class="mt-2">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                placeholder="••••••••"
                                class="block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white dark:placeholder-slate-500 dark:focus:border-indigo-400"
                            >
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            Konfirmasi Password Baru
                        </label>
                        <div class="mt-2">
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                required 
                                placeholder="••••••••"
                                class="block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white dark:placeholder-slate-500 dark:focus:border-indigo-400"
                            >
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit"
                            class="flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition duration-150"
                        >
                            Perbarui Password
                        </button>
                    </div>
                </form>

            </div>

            <!-- Footer info -->
            <p class="mt-8 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} Teman Seakad. All rights reserved.
            </p>

        </div>
    </div>

</body>
</html>
