{{-- 
    RSVP — Konfirmasi Kehadiran
    
    Premium form styling with theme token system.
    Integrated with scroll reveal animations.
--}}

<section class="py-12 px-6 space-y-8 relative" style="border-bottom: 1px solid var(--theme-secondary);" {!! themeAnimation('rsvp') !!}>
    {{-- Section Header --}}
    <div class="section-header fade-up" data-animation>
        <h2 class="section-title" style="font-family: var(--theme-font-heading); color: var(--theme-primary);">Konfirmasi Kehadiran</h2>
        <div class="section-line"></div>
        <p class="section-desc mt-3" style="font-family: var(--theme-font-body);">
            Silakan konfirmasi kehadiran Anda melalui formulir di bawah ini:
        </p>
    </div>

    <form 
        x-data="rsvpForm('{{ route('public.invitation.rsvp', $invitation->slug) }}')"
        @submit.prevent="submit"
        class="space-y-4 max-w-md mx-auto text-left fade-up"
        data-animation
        style="animation-delay: 0.15s;"
    >
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" 
                   style="color: var(--theme-text); opacity: 0.6; font-family: var(--theme-font-body);">Nama Lengkap</label>
            <input 
                type="text" 
                x-model="form.name"
                required
                placeholder="Contoh: Budi Santoso"
                class="w-full px-4 py-3 text-sm transition duration-200 outline-none"
                style="border-radius: var(--theme-radius); border: 1px solid var(--theme-secondary); background: var(--theme-surface); color: var(--theme-text); font-family: var(--theme-font-body);"
            />
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" 
                   style="color: var(--theme-text); opacity: 0.6; font-family: var(--theme-font-body);">Nomor WhatsApp</label>
            <input 
                type="text" 
                x-model="form.phone"
                placeholder="Contoh: 08123456789"
                class="w-full px-4 py-3 text-sm transition duration-200 outline-none"
                style="border-radius: var(--theme-radius); border: 1px solid var(--theme-secondary); background: var(--theme-surface); color: var(--theme-text); font-family: var(--theme-font-body);"
            />
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" 
                   style="color: var(--theme-text); opacity: 0.6; font-family: var(--theme-font-body);">Konfirmasi Kehadiran</label>
            <select 
                x-model="form.attendance"
                required
                class="w-full px-4 py-3 text-sm transition duration-200 outline-none"
                style="border-radius: var(--theme-radius); border: 1px solid var(--theme-secondary); background: var(--theme-surface); color: var(--theme-text); font-family: var(--theme-font-body);"
            >
                <option value="hadir">Saya Akan Hadir</option>
                <option value="tidak_hadir">Maaf, Saya Tidak Bisa Hadir</option>
                <option value="belum_pasti">Belum Pasti</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" 
                   style="color: var(--theme-text); opacity: 0.6; font-family: var(--theme-font-body);">Ucapan & Doa Restu</label>
            <textarea 
                x-model="form.message"
                rows="4"
                placeholder="Tuliskan ucapan selamat dan doa restu Anda..."
                class="w-full px-4 py-3 text-sm transition duration-200 outline-none resize-none"
                style="border-radius: var(--theme-radius); border: 1px solid var(--theme-secondary); background: var(--theme-surface); color: var(--theme-text); font-family: var(--theme-font-body);"
            ></textarea>
        </div>

        <button 
            type="submit" 
            :disabled="loading"
            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 font-bold text-sm uppercase tracking-widest shadow-md transition duration-300 disabled:opacity-50 cursor-pointer"
            style="border-radius: var(--theme-radius); background: var(--theme-primary); color: var(--theme-surface);"
        >
            <svg x-show="loading" class="animate-spin h-4 w-4" style="color: var(--theme-surface);" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Kirim Konfirmasi
        </button>

        {{-- Toast / Alert --}}
        <div 
            x-show="message" 
            x-transition
            :class="success ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-600' : 'bg-rose-500/10 border-rose-500/20 text-rose-600'"
            class="p-4 border text-xs text-center"
            style="border-radius: var(--theme-radius); font-family: var(--theme-font-body);"
            x-text="message"
        ></div>
    </form>
</section>

@once
<script>
    document.addEventListener('alpine:init', () => {
        if (window.rsvpFormInited) return;
        window.rsvpFormInited = true;
        
        Alpine.data('rsvpForm', (actionUrl) => ({
            form: {
                name: '',
                phone: '',
                attendance: 'hadir',
                message: ''
            },
            loading: false,
            success: false,
            message: '',

            submit() {
                this.loading = true;
                this.message = '';
                
                window.axios.post(actionUrl, this.form)
                    .then(response => {
                        this.loading = false;
                        this.success = true;
                        this.message = response.data.message;
                        
                        // Emit event so guest-wish section knows it should refresh wishes
                        window.dispatchEvent(new CustomEvent('rsvp-submitted', { detail: { name: this.form.name, message: this.form.message } }));
                        
                        this.form.name = '';
                        this.form.phone = '';
                        this.form.attendance = 'hadir';
                        this.form.message = '';
                    })
                    .catch(error => {
                        this.loading = false;
                        this.success = false;
                        this.message = error.response?.data?.message || 'Terjadi kesalahan. Silakan coba lagi.';
                    });
            }
        }));
    });
</script>
@endonce