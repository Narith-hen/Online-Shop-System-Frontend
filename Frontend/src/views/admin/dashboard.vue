<template>
  <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg text-center">
      <h1 class="text-3xl font-bold text-gray-800 mb-8">
        Welcome to Home Page Admin Shop
      </h1>

      <button
        @click="handleLogout"
        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-200 flex items-center gap-2 mx-auto"
      >
        <span>Logout</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  router.push('/login')
}
</script>
