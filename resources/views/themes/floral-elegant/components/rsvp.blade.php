<section class="py-16 px-6 border-b border-[var(--theme-secondary)]/50 space-y-8" {!! themeAnimation('rsvp') !!}>
    <div class="text-center space-y-2">
        <h2 class="text-2xl font-bold uppercase tracking-widest text-[var(--theme-primary)]" style="font-family: var(--theme-font-heading);">Konfirmasi Kehadiran</h2>
        <div class="h-[1px] w-12 bg-[var(--theme-primary)] mx-auto mt-2 opacity-50"></div>
        <p class="text-xs text-[var(--theme-text)] opacity-75 max-w-xs mx-auto mt-4 font-body" style="font-family: var(--theme-font-body);">
            Silakan konfirmasi kehadiran Anda melalui formulir di bawah ini:
        </p>
    </div>

    <form 
        x-data="rsvpForm('{{ route('public.invitation.rsvp', $invitation->slug) }}')"
        @submit.prevent="submit"
        class="space-y-4 max-w-md mx-auto text-left"
    >
        <div>
            <label class="block text-xs font-semibold text-[var(--theme-text)] opacity-70 uppercase tracking-wider mb-1.5 font-body" style="font-family: var(--theme-font-body);">Nama Lengkap</label>
            <input 
                type="text" 
                x-model="form.name"
                required
                placeholder="Contoh: Budi Santoso"
                class="w-full rounded-xl border border-[var(--theme-secondary)] bg-[var(--theme-surface)]/80 px-4 py-3 text-sm text-[var(--theme-text)] focus:border-[var(--theme-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary)] transition duration-200 font-body"
                style="font-family: var(--theme-font-body);"
            />
        </div>

        <div>
            <label class="block text-xs font-semibold text-[var(--theme-text)] opacity-70 uppercase tracking-wider mb-1.5 font-body" style="font-family: var(--theme-font-body);">Nomor WhatsApp</label>
            <input 
                type="text" 
                x-model="form.phone"
                placeholder="Contoh: 08123456789"
                class="w-full rounded-xl border border-[var(--theme-secondary)] bg-[var(--theme-surface)]/80 px-4 py-3 text-sm text-[var(--theme-text)] focus:border-[var(--theme-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary)] transition duration-200 font-body"
                style="font-family: var(--theme-font-body);"
            />
        </div>

        <div>
            <label class="block text-xs font-semibold text-[var(--theme-text)] opacity-70 uppercase tracking-wider mb-1.5 font-body" style="font-family: var(--theme-font-body);">Konfirmasi Kehadiran</label>
            <select 
                x-model="form.attendance"
                required
                class="w-full rounded-xl border border-[var(--theme-secondary)] bg-[var(--theme-surface)]/80 px-4 py-3 text-sm text-[var(--theme-text)] focus:border-[var(--theme-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary)] transition duration-200 font-body"
                style="font-family: var(--theme-font-body);"
            >
                <option value="hadir">Saya Akan Hadir</option>
                <option value="tidak_hadir">Maaf, Saya Tidak Bisa Hadir</option>
                <option value="belum_pasti">Belum Pasti</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[var(--theme-text)] opacity-70 uppercase tracking-wider mb-1.5 font-body" style="font-family: var(--theme-font-body);">Ucapan & Doa Restu</label>
            <textarea 
                x-model="form.message"
                rows="4"
                placeholder="Tuliskan ucapan selamat dan doa restu Anda..."
                class="w-full rounded-xl border border-[var(--theme-secondary)] bg-[var(--theme-surface)]/80 px-4 py-3 text-sm text-[var(--theme-text)] focus:border-[var(--theme-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--theme-primary)] transition duration-200 font-body"
                style="font-family: var(--theme-font-body);"
            ></textarea>
        </div>

        <button 
            type="submit" 
            :disabled="loading"
            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[var(--theme-primary)] text-[var(--theme-surface)] hover:bg-[var(--theme-primary)]/90 font-bold text-sm uppercase tracking-widest shadow-md transition duration-300 disabled:opacity-50 cursor-pointer"
        >
            <svg x-show="loading" class="animate-spin h-4 w-4 text-[var(--theme-surface)]" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Kirim Konfirmasi
        </button>

        <!-- Toast / Alert Response message -->
        <div 
            x-show="message" 
            x-transition
            :class="success ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-600' : 'bg-rose-500/10 border-rose-500/20 text-rose-600'"
            class="p-4 rounded-xl border text-xs text-center font-body"
            style="font-family: var(--theme-font-body);"
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