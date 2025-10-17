import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import 'leaflet/dist/leaflet.css'

// ✅ Konfigurasi axios global
axios.defaults.baseURL = 'http://127.0.0.1:8000/api' // pakai URL penuh agar stabil
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// ✅ Jika kamu pakai Sanctum nanti untuk admin login:
// axios.defaults.withCredentials = true

// Buat app Vue
const app = createApp(App)

// ✅ Tambahkan axios ke global properties (bisa diakses via this.$axios)
app.config.globalProperties.$axios = axios

// Gunakan router Vue
app.use(router)

// Mount aplikasi
app.mount('#app')
