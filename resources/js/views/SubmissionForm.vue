<template>
  <div class="flex flex-col min-h-screen font-poppins bg-gray-100">
    <main class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8">
      <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl p-8 sm:p-10 border border-gray-200">
        <h2 class="text-3xl font-bold text-center text-blue-900 mb-2">Form Pengajuan Budaya</h2>
        <p class="text-center text-gray-600 mb-8">Bagikan dan lestarikan budayamu bersama kami.</p>

        <form class="space-y-6" @submit.prevent="handleSubmit">

          <!-- STATUS MAHASISWA -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Apakah Anda Mahasiswa?</label>
            <select v-model="statusMahasiswa" class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500">
              <option disabled value="">-- Pilih Status Anda --</option>
              <option value="mahasiswa">Ya, Saya Mahasiswa</option>
              <option value="bukan">Bukan, Saya Umum</option>
            </select>
          </div>

          <transition name="fade">
            <div v-if="statusMahasiswa === 'mahasiswa'">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Induk Mahasiswa (NIM)</label>
              <input v-model="form.nim" type="text" placeholder="Masukkan NIM Anda"
                     class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500"/>
            </div>
          </transition>

          <!-- IDENTITAS PENGIRIM -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pengirim</label>
              <input v-model="form.nama" type="text" placeholder="Masukkan nama lengkap"
                     class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
              <input v-model="form.email" type="email" placeholder="contoh@email.com"
                     class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500"/>
            </div>
          </div>

          <!-- INFO BUDAYA -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Budaya</label>
            <input v-model="form.nama_budaya" type="text"
                   placeholder="Contoh: Tari Piring, Upacara Seren Taun"
                   class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500"/>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Budaya</label>
            <textarea v-model="form.deskripsi" rows="4"
                      placeholder="Jelaskan secara singkat mengenai budaya..."
                      class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
          </div>

          <!-- 📍 PETA INTERAKTIF -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tentukan Lokasi Budaya di Peta</label>
            <div id="map" class="w-full h-[350px] rounded-lg border border-gray-300"></div>

            <div class="mt-3 grid grid-cols-2 gap-4">
              <div>
                <label class="text-xs text-gray-600">Latitude</label>
                <input v-model="form.latitude" readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"/>
              </div>
              <div>
                <label class="text-xs text-gray-600">Longitude</label>
                <input v-model="form.longitude" readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"/>
              </div>
            </div>
          </div>

          <!-- FOTO -->
          <div class="space-y-3 flex flex-col items-center">
            <label class="block text-sm font-semibold text-gray-700">Upload Foto (Max 500MB)</label>
            <div v-for="(foto, index) in fotos" :key="index" class="flex items-center gap-3">
              <input type="file" accept="image/*" @change="handleFotoChange($event, index)"
                     class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
              <button v-if="fotos.length > 1" type="button" @click="removeFoto(index)"
                      class="p-2 bg-rose-500 text-white rounded-full hover:bg-rose-600">
                ✕
              </button>
            </div>
            <button type="button" @click="addFoto"
                    class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
              + Tambah Foto
            </button>
          </div>

          <!-- KONTAK -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Kontak</label>
            <input v-model="form.telp" type="tel" placeholder="Contoh: 0812xxxxxxx"
                   class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500"/>
          </div>

          <!-- TOMBOL SUBMIT -->
          <div class="pt-4">
            <button type="submit"
                    class="w-full py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition-all duration-300">
              Kirim Pengajuan
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import L from 'leaflet'
import axios from 'axios'

const statusMahasiswa = ref('')
const fotos = ref([{}])
const form = ref({
  nim: '',
  nama: '',
  email: '',
  nama_budaya: '',
  deskripsi: '',
  latitude: '',
  longitude: '',
  telp: ''
})

let map = null
let marker = null

// 🌍 Inisialisasi Peta
onMounted(() => {
  map = L.map('map').setView([-2.5, 118], 5)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
  }).addTo(map)

  // Klik untuk menambah pin
  map.on('click', (e) => {
    const { lat, lng } = e.latlng
    form.value.latitude = lat.toFixed(6)
    form.value.longitude = lng.toFixed(6)

    // Hapus marker lama
    if (marker) {
      map.removeLayer(marker)
    }

    // Tambah marker baru
    marker = L.marker([lat, lng]).addTo(map)
  })
})

// 🖼️ Tambah / Hapus Foto
function addFoto() {
  fotos.value.push({})
}
function removeFoto(index) {
  if (fotos.value.length > 1) fotos.value.splice(index, 1)
}

// Simpan foto
function handleFotoChange(e, index) {
  fotos.value[index] = e.target.files[0]
}

// 📤 Submit Form
async function handleSubmit() {
  try {
    const formData = new FormData()
    for (const key in form.value) {
      formData.append(key, form.value[key])
    }

    fotos.value.forEach((file, index) => {
      if (file instanceof File) {
        formData.append(`photos[${index}]`, file)
      }
    })

    await axios.post('/cultures', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    alert('Pengajuan budaya berhasil dikirim!')
    location.reload()
  } catch (err) {
    console.error(err)
    alert('Terjadi kesalahan saat mengirim pengajuan.')
  }
}
</script>

<style scoped>
#map {
  height: 350px;
  width: 100%;
  border-radius: 8px;
}
</style>
