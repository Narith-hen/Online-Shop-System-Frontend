<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Guest guard: redirect logged-in users away from login page
onMounted(() => {
  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || '{}')

  if (token) {
    if (user.role === 'admin') {
      router.replace('/dashboard')
    } else {
      router.replace('/home')
    }
  }
})
const email = ref('')
const password = ref('')
const loading = ref(false)
const message = ref('')
const showPassword = ref(false)
const socialLoading = ref(null)

const handleLogin = async () => {
  if (!email.value || !password.value) {
    message.value = 'Please fill in all fields'
    return
  }

  loading.value = true
  message.value = ''

  try {
    const apiUrl = import.meta.env.VITE_API_BASE_URL
    const response = await fetch(`${apiUrl}/api/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        email: email.value,
        password: password.value,
      }),
    })

    const data = await response.json()

    if (data.success) {
      localStorage.setItem('token', data.token)

      localStorage.setItem(
        'user',
        JSON.stringify({
          id: data.user?.id,
          name: data.user?.name,
          email: data.user?.email,
          role: data.role, // Make sure role is included
        }),
      )

      email.value = ''
      password.value = ''
      message.value = 'Login successful!'

      setTimeout(() => {
        if (data.role === 'admin') {
          router.push('/dashboard')
        } else if (data.role === 'customer') {
          router.push('/home')
        } else {
          router.push('/')
        }
      }, 1000)
    } else {
      message.value = data.message || 'Login failed'
    }
  } catch (error) {
    message.value = 'Error: ' + error.message
  } finally {
    loading.value = false
  }
}

const handleSocialLogin = (provider) => {
  socialLoading.value = provider
  const apiUrl = import.meta.env.VITE_API_BASE_URL
  window.location.href = `${apiUrl}/api/auth/${provider}/redirect`
}

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-black-300 flex items-center justify-center p-4">
    <!-- Login Card -->
    <div class="w-full max-w-md">
      <!-- Logo/Header -->
      <div class="text-center mb-8">
        <div class="inline-block bg-white rounded-full">
          <img src="/images/logo.png" alt="" class="w-32" />
        </div>
        <h1 class="text-4xl font-bold text-black-600 mb-2">Online Shop System</h1>
        <p class="text-gray-500">Sign in to your account</p>
      </div>

      <!-- Form Card -->
      <div class="bg-white rounded-lg shadow-2xl p-8 space-y-6">
        <!-- Message Alert -->
        <div
          v-if="message"
          :class="[
            'p-4 rounded-lg text-center text-sm font-medium',
            message.includes('')
              ? 'bg-green-100 text-green-700 border border-green-300'
              : 'bg-red-100 text-red-700 border border-red-300',
          ]"
        >
          {{ message }}
        </div>

        <!-- Email Field -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2"> Email Address </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
              </svg>
            </div>
            <input
              v-model="email"
              type="email"
              placeholder="narith@gmail.com"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
            />
          </div>
        </div>

        <!-- Password Field -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2"> Password </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                  clip-rule="evenodd"
                />
              </svg>
            </div>
            <input
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="Enter your password"
              class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
            />
            <button
              @click="togglePasswordVisibility"
              type="button"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
            >
              <svg v-if="!showPassword" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path
                  fill-rule="evenodd"
                  d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                  clip-rule="evenodd"
                />
              </svg>
              <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                  clip-rule="evenodd"
                />
                <path
                  d="M15.171 13.576l1.414 1.414a1 1 0 00.707.293 1 1 0 00.707-1.707l-14-14a1 1 0 00-1.414 1.414l1.473 1.473A10.014 10.014 0 00.458 10c1.274 4.057 5.064 7 10 7a9.958 9.958 0 004.512-1.074l1.781 1.781zm4.065-4.294a1 1 0 00-1.414-1.414L5.13 9.13a1 1 0 101.414 1.414L18.436 9.582z"
                />
              </svg>
            </button>
          </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
          <label class="flex items-center space-x-2">
            <input
              type="checkbox"
              class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
            />
            <span class="text-sm text-gray-600">Remember me</span>
          </label>
          <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            Forgot password?
          </a>
        </div>

        <!-- Login Button -->
        <button
          @click="handleLogin"
          :disabled="loading"
          class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg"
        >
          <span v-if="!loading">Sign In</span>
          <span v-else class="flex items-center justify-center">
            <svg
              class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
              ></path>
            </svg>
            Signing in...
          </span>
        </button>

        <!-- Divider -->
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or continue with</span>
          </div>
        </div>

        <!-- Social Login -->
        <div class="grid grid-cols-2 gap-4">
          <button
            @click="handleSocialLogin('google')"
            :disabled="socialLoading !== null"
            class="flex items-center justify-center gap-2 py-2.5 px-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-wait"
          >
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
              />
              <path
                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
              />
              <path
                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
              />
              <path
                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
              />
            </svg>
            <span class="text-sm text-gray-600">
              {{ socialLoading === 'google' ? 'Redirecting...' : 'Google' }}
            </span>
          </button>
          <button
            @click="handleSocialLogin('github')"
            :disabled="socialLoading !== null"
            class="flex items-center justify-center gap-2 py-2.5 px-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-wait"
          >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v 3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"
              />
            </svg>
            <span class="text-sm text-gray-600">
              {{ socialLoading === 'github' ? 'Redirecting...' : 'GitHub' }}
            </span>
          </button>
        </div>

        <!-- Sign Up Link -->
        <p class="text-center text-gray-600">
          Don't have an account?
          <a href="/register" class="text-blue-600 hover:text-blue-700 font-semibold">
            Sign up here
          </a>
        </p>
      </div>

      <!-- Footer -->
      <p class="text-center text-gray-500 text-sm mt-8">
        By signing in, you agree to our
        <a href="#" class="underline hover:text-white">Terms of Service</a>
        and
        <a href="#" class="underline hover:text-white">Privacy Policy</a>
      </p>
    </div>
  </div>
</template>
