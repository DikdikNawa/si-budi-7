import { createRouter, createWebHistory } from 'vue-router'
import MapView from './views/MapView.vue'
import SubmissionForm from './views/SubmissionForm.vue'
import AboutView from './views/AboutView.vue'
// import Login from './views/Login.vue'
import AdminDashboard from './views/AdminDashboard.vue'

const routes = [
  { path: '/', component: MapView },
  { path: '/ajukan', component: SubmissionForm },
  { path: '/tentang', component: AboutView },
  // { path: '/login', component: Login },
  { path: '/admin', component: AdminDashboard },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
