<template>
  <div>
    <!-- Navbar sederhana -->
    <nav class="flex justify-between items-center bg-blue-600 text-white px-6 py-3">
      <h1 class="font-bold text-lg">Peta Budaya Indonesia</h1>
      <div class="flex gap-4">
        <RouterLink to="/">Peta</RouterLink>
        <RouterLink to="/ajukan">Ajukan Budaya</RouterLink>
        <RouterLink to="/admin">Admin</RouterLink>
      </div>
    </nav>

    <!-- Router View untuk menampilkan halaman aktif -->
    <main class="p-4">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
// tidak perlu script tambahan di root component
</script>

<style scoped>
nav a {
  color: white;
  text-decoration: none;
  font-weight: 500;
}

nav a.router-link-active {
  text-decoration: underline;
}
</style>
