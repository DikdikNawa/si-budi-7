<template>
  <div>
    <h2>Dashboard Admin</h2>
    <table border="1">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Jenis</th>
          <th>Pengaju</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="c in cultures" :key="c.id">
          <td>{{ c.name }}</td>
          <td>{{ c.type }}</td>
          <td>{{ c.submitted_by }}</td>
          <td>{{ c.status }}</td>
          <td>
            <button @click="approve(c.id)">Setujui</button>
            <button @click="reject(c.id)">Tolak</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const cultures = ref([])

onMounted(async () => {
  const { data } = await axios.get('/api/admin/cultures')
  cultures.value = data
})

const approve = async id => {
  await axios.put(`/api/admin/cultures/${id}/status`, { status: 'approved', curated_by: 'Admin' })
  alert('Disetujui!')
}
const reject = async id => {
  await axios.put(`/api/admin/cultures/${id}/status`, { status: 'rejected', curated_by: 'Admin' })
  alert('Ditolak!')
}
</script>
