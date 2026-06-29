<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()
const status = ref('Processing your login...')
const error = ref(null)

onMounted(() => {
  const token = route.query.token
  const userParam = route.query.user
  const errorParam = route.query.error

  if (errorParam) {
    error.value = errorParam
    status.value = 'Login failed'
    setTimeout(() => {
      router.push('/login')
    }, 3000)
    return
  }

  if (token && userParam) {
    try {
      const user = JSON.parse(decodeURIComponent(userParam))

      localStorage.setItem('token', token)
      localStorage.setItem(
        'user',
        JSON.stringify({
          id: user.id,
          name: user.name,
          email: user.email,
          role: user.role,
          avatar: user.avatar,
        }),
      )

      status.value = 'Login successful! Redirecting...'

      setTimeout(() => {
        router.push('/home')
      }, 500)
    } catch (e) {
      error.value = 'Failed to process login response'
      status.value = 'Error'
      setTimeout(() => {
        router.push('/login')
      }, 3000)
    }
  } else {
    error.value = 'Invalid callback: missing authentication data'
    status.value = 'Error'
    setTimeout(() => {
      router.push('/login')
    }, 3000)
  }
})
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-12 max-w-md w-full text-center animate-scale-in">
      <!-- Loading Spinner -->
      <div v-if="!error" class="mb-6">
        <svg
          class="animate-spin mx-auto h-12 w-12 text-blue-500"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
          ></path>
        </svg>
      </div>

      <!-- Error Icon -->
      <div v-else class="mb-6">
        <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
      </div>

      <h2 v-if="!error" class="text-2xl font-bold text-gray-900 mb-2">{{ status }}</h2>
      <h2 v-else class="text-2xl font-bold text-red-600 mb-2">{{ status }}</h2>
      <p v-if="error" class="text-gray-600">{{ error }}</p>
      <p v-else class="text-gray-500">Please wait while we complete your authentication...</p>
    </div>
  </div>
</template>
