<template>
  <div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
      <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-12 text-center animate-scale-in">
        <!-- Shield Icon -->
        <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6 animate-fade-in-down">
          <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 15v2m0 0v2m0-2h2m-2 0H10m9.364-7.364A9 9 0 1112 3a9 9 0 017.364 4.636z" />
          </svg>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-900 mb-3 animate-fade-in">Account Blocked</h1>

        <!-- Message -->
        <p class="text-gray-600 mb-8 leading-relaxed">
          Your account has been blocked by the administrator.
          You are unable to access your account or place new orders at this time.
        </p>

        <!-- Contact Support -->
        <div class="bg-gray-50 rounded-xl p-5 mb-8 text-left">
          <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Need help?
          </h2>
          <p class="text-sm text-gray-500 leading-relaxed">
            If you believe this is a mistake or would like to appeal, please contact our support team:
          </p>
          <p class="text-sm font-medium text-gray-700 mt-2">
            support@onlineshop.com
          </p>
        </div>

        <!-- Back to Login -->
        <router-link
          to="/login"
          class="inline-block bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-10 rounded-xl transition duration-200 animate-fade-in-up"
        >
          Back to Login
        </router-link>
      </div>

      <p class="text-center text-gray-400 text-xs mt-6">
        Online Shop System &copy; {{ new Date().getFullYear() }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
})
</script>
