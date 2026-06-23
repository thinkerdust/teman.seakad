<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  slug: String
})

const name = ref('')
const phone = ref('')
const attendance = ref('hadir')
const message = ref('')
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

onMounted(() => {
  // Prefill nama penerima jika ada dari query parameter / window data
  if (window.invitationData && window.invitationData.recipient_name) {
    name.value = window.invitationData.recipient_name
  }
})

const submitRsvp = () => {
  loading.value = true
  successMessage.value = ''
  errorMessage.value = ''

  axios.post(`/${props.slug}/rsvp`, {
    name: name.value,
    phone: phone.value,
    attendance: attendance.value,
    message: message.value
  })
  .then(response => {
    loading.value = false
    if (response.data.success) {
      successMessage.value = response.data.message
      // Kosongkan form kecuali nama
      phone.value = ''
      message.value = ''
    } else {
      errorMessage.value = 'Gagal mengirim konfirmasi kehadiran.'
    }
  })
  .catch(error => {
    loading.value = false
    if (error.response && error.response.data && error.response.data.errors) {
      const errors = error.response.data.errors
      errorMessage.value = Object.values(errors).map(err => err[0]).join(', ')
    } else {
      errorMessage.value = 'Terjadi kesalahan sistem saat mengirim RSVP.'
    }
  })
}
</script>

<template>
  <div class="rsvp-section bg-stone-50 border-t border-stone-200/50 py-12 px-6">
    <div class="max-w-md mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-stone-200">
      <div class="text-center mb-6">
        <span class="text-xs font-bold text-amber-600 uppercase tracking-widest">RSVP</span>
        <h2 class="text-2xl font-bold font-serif text-stone-800 mt-1">Konfirmasi Kehadiran</h2>
        <p class="text-xs text-stone-500 mt-2">Mohon konfirmasi kehadiran Anda melalui formulir di bawah ini</p>
      </div>

      <!-- Success Alert -->
      <div v-if="successMessage" class="mb-4 p-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-semibold text-center border border-emerald-100">
        {{ successMessage }}
      </div>

      <!-- Error Alert -->
      <div v-if="errorMessage" class="mb-4 p-4 rounded-xl bg-rose-50 text-rose-700 text-sm font-semibold text-center border border-rose-100">
        {{ errorMessage }}
      </div>

      <form @submit.prevent="submitRsvp" class="space-y-4">
        <!-- Nama -->
        <div>
          <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
          <input 
            type="text" 
            v-model="name"
            required
            placeholder="Masukkan nama Anda"
            class="w-full rounded-xl border border-stone-200 bg-stone-50/50 px-4 py-2.5 text-sm text-stone-850 placeholder-stone-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
          />
        </div>

        <!-- Telepon -->
        <div>
          <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Nomor Telepon (WhatsApp)</label>
          <input 
            type="text" 
            v-model="phone"
            placeholder="Contoh: 08123456789"
            class="w-full rounded-xl border border-stone-200 bg-stone-50/50 px-4 py-2.5 text-sm text-stone-850 placeholder-stone-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
          />
        </div>

        <!-- Kehadiran -->
        <div>
          <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Konfirmasi Kehadiran *</label>
          <select 
            v-model="attendance"
            required
            class="w-full rounded-xl border border-stone-200 bg-stone-50/50 px-4 py-2.5 text-sm text-stone-850 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
          >
            <option value="hadir">Ya, Saya Akan Hadir</option>
            <option value="tidak_hadir">Maaf, Saya Tidak Bisa Hadir</option>
            <option value="belum_pasti">Belum Pasti Hadir</option>
          </select>
        </div>

        <!-- Pesan -->
        <div>
          <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Ucapan & Doa Restu</label>
          <textarea 
            v-model="message"
            rows="3"
            placeholder="Tulis ucapan selamat dan doa restu Anda di sini..."
            class="w-full rounded-xl border border-stone-200 bg-stone-50/50 px-4 py-2.5 text-sm text-stone-850 placeholder-stone-400 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
          ></textarea>
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          :disabled="loading"
          class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 hover:bg-amber-700 px-4 py-3 text-sm font-bold text-white shadow-md hover:shadow-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <svg v-if="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Kirim Konfirmasi
        </button>
      </form>
    </div>
  </div>
</template>
