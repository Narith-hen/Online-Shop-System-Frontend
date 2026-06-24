<template>
  <nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-3">
          <img src="/images/logo.png" alt="Online Shop" class="h-10 w-auto" />
          <span class="text-2xl font-bold text-gray-900">Online Shop</span>
        </router-link>

        <!-- Navigation Links (customer only) -->
        <div v-if="isLoggedIn && isCustomer" class="hidden md:flex gap-8">
          <router-link to="/home" class="text-gray-700 hover:text-blue-600 font-medium transition">
            Home
          </router-link>
          <router-link to="/products" class="text-gray-700 hover:text-blue-600 font-medium transition">
            Products
          </router-link>
          <router-link to="/about" class="text-gray-700 hover:text-blue-600 font-medium transition">
            About
          </router-link>
          <router-link to="/contact" class="text-gray-700 hover:text-blue-600 font-medium transition">
            Contact
          </router-link>
        </div>

        <!-- Welcome page nav links (guest) -->
        <div v-else-if="!isLoggedIn" class="hidden md:flex gap-8">
          <a href="#features" class="text-gray-700 hover:text-blue-600 font-medium transition">Features</a>
          <a href="#testimonials" class="text-gray-700 hover:text-blue-600 font-medium transition">Testimonials</a>
          <a href="#pricing" class="text-gray-700 hover:text-blue-600 font-medium transition">Pricing</a>
          <a href="#contact" class="text-gray-700 hover:text-blue-600 font-medium transition">Contact</a>
        </div>

        <!-- Right side: auth actions -->
        <div class="flex items-center gap-4">
          <!-- Guest: show Login button -->
          <template v-if="!isLoggedIn">
            <router-link
              to="/login"
              class="text-gray-700 hover:text-blue-600 font-medium transition"
            >
              Login
            </router-link>
          </template>

          <!-- Logged-in customer: cart + profile dropdown -->
          <template v-else-if="isCustomer">
            <!-- Cart Icon -->
            <button class="relative text-gray-700 hover:text-blue-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                ></path>
              </svg>
              <span
                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
              >3</span>
            </button>

            <!-- Profile Dropdown -->
            <div class="relative group">
              <button class="text-gray-700 hover:text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  ></path>
                </svg>
              </button>
              <div class="absolute right-0 w-48 bg-white rounded-lg shadow-xl hidden group-hover:block">
                <router-link to="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  My Profile
                </router-link>
                <router-link to="/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  My Orders
                </router-link>
                <router-link to="/settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  Settings
                </router-link>
                <hr class="my-2" />
                <button
                  @click="handleLogout"
                  class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
                >
                  <span>Logout</span>
                </button>
              </div>
            </div>
          </template>

          <!-- Logged-in admin: admin link + logout -->
          <template v-else-if="isAdmin">
            <router-link
              to="/dashboard"
              class="text-gray-700 hover:text-blue-600 font-medium transition"
            >
              Dashboard
            </router-link>
            <button
              @click="handleLogout"
              class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition"
            >
              Logout
            </button>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

// Re-read localStorage on every route change to stay reactive
const authState = ref({ token: null, user: {} })

const refreshAuth = () => {
  const token = localStorage.getItem('token')
  let user = {}
  try {
    user = JSON.parse(localStorage.getItem('user') || '{}')
  } catch {
    user = {}
  }
  authState.value = { token, user }
}

// Read on mount and re-read on every navigation
refreshAuth()
route.matched // access to trigger reactivity on route change
const isLoggedIn = computed(() => !!authState.value.token)
const isCustomer = computed(() => authState.value.user.role === 'customer')
const isAdmin = computed(() => authState.value.user.role === 'admin')

// Watch route changes to refresh auth state
import { watch } from 'vue'
watch(
  () => route.fullPath,
  () => refreshAuth()
)

const handleLogout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  refreshAuth()
  router.push('/login')
}
</script>
