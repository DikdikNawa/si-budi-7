<template>
  <div class="relative w-full h-[90vh]">
    <div id="map" class="w-full h-full rounded"></div>

    <!-- 🔍 Kotak pencarian -->
    <div class="absolute top-4 left-4 bg-white p-3 rounded shadow z-[1000] w-64">
      <input
        v-model="search"
        @input="filterMarkers"
        type="text"
        placeholder="Cari budaya..."
        class="w-full border rounded p-2"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import L from 'leaflet'
import axios from 'axios'

const map = ref(null)
const markers = ref([])
const cultures = ref([])
const search = ref('')

let lockedMarker = null // 🔒 Simpan marker yang sedang dikunci (diklik)

// 🔹 Ambil semua data budaya (beserta media)
async function loadCultures() {
  try {
    const response = await axios.get('/cultures')
    cultures.value = response.data
    renderMarkers(cultures.value)
  } catch (error) {
    console.error('Gagal memuat data budaya:', error)
  }
}

// 🔹 Render pin di peta
function renderMarkers(data) {
  markers.value.forEach(marker => map.value.removeLayer(marker))
  markers.value = []

  data.forEach(item => {
    if (!item.latitude || !item.longitude) return

    const photos = item.media?.filter(m => m.type === 'photo') || []
    const videos = item.media?.filter(m => m.type === 'video') || []
    const audios = item.media?.filter(m => m.type === 'audio') || []

    let photoUrl = '/images/default-culture.jpg'
    if (photos.length && photos[0].url) {
      if (photos[0].url.startsWith('storage')) {
        photoUrl = `/${photos[0].url}`
      } else {
        photoUrl = `/storage/${photos[0].url.replace(/^\/?storage\//, '')}`
      }
    }

    let popupContent = `
      <div style="max-width:260px;">
        <h3><b>${item.name}</b></h3>
        <p>${item.description || '-'}</p>
        <p><small><i>Kategori: ${item.category}</i></small></p>
        <img src="${photoUrl}" alt="${item.name}" 
             style="width:100%;border-radius:8px;margin-top:6px;">
    `

    if (videos.length) {
      const videoUrl = videos[0].url.startsWith('http')
        ? videos[0].url
        : `/storage/${videos[0].url.replace(/^\/?storage\//, '')}`
      popupContent += `
        <p style="margin-top:6px;">
          <a href="${videoUrl}" target="_blank">🎥 Lihat Video</a>
        </p>
      `
    }

    if (audios.length) {
      const audioUrl = `/storage/${audios[0].url.replace(/^\/?storage\//, '')}`
      popupContent += `
        <audio controls style="width:100%;margin-top:6px;">
          <source src="${audioUrl}" type="audio/mpeg">
          Browser Anda tidak mendukung audio.
        </audio>
      `
    }

    popupContent += `</div>`

    const marker = L.marker([item.latitude, item.longitude]).addTo(map.value)
    marker.bindPopup(popupContent)

    // 🖱️ Hover → buka popup
    marker.on('mouseover', function () {
      if (lockedMarker !== this) {
        this.openPopup()
      }
    })

    // 🖱️ Mouse keluar → tutup popup (jika tidak terkunci)
    marker.on('mouseout', function () {
      if (lockedMarker !== this) {
        this.closePopup()
      }
    })

    // 🖱️ Klik → toggle "lock"
    marker.on('click', function () {
      if (lockedMarker === this) {
        // Jika marker ini sudah dikunci, klik lagi untuk buka kunci
        lockedMarker = null
        this.closePopup()
      } else {
        // Tutup popup marker lain dulu
        if (lockedMarker) {
          lockedMarker.closePopup()
        }
        lockedMarker = this
        this.openPopup()
      }
    })

    markers.value.push(marker)
  })
}

// 🔹 Filter hasil pencarian
function filterMarkers() {
  const keyword = search.value.toLowerCase()
  const filtered = cultures.value.filter(item =>
    item.name.toLowerCase().includes(keyword) ||
    item.category.toLowerCase().includes(keyword) ||
    (item.submitted_by && item.submitted_by.toLowerCase().includes(keyword))
  )
  renderMarkers(filtered)
}

// 🔹 Saat komponen dimuat
onMounted(() => {
  map.value = L.map('map').setView([-6.914744, 107.60981], 5)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map.value)

  loadCultures()

  // 🧭 Klik di area kosong map → buka kunci popup
  map.value.on('click', () => {
    if (lockedMarker) {
      lockedMarker.closePopup()
      lockedMarker = null
    }
  })
})
</script>

<style scoped>
#map {
  height: 90vh;
}
</style>
