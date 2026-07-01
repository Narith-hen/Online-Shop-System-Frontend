<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { post } from '@/services/api'

const router = useRouter()

// Guest guard: redirect logged-in users away from register page
onMounted(() => {
  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || '{}')

  if (token) {
    router.replace('/home')
  }
})

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const loading = ref(false)
const message = ref('')
const message_type = ref('error') // 'error' | 'success'
const errors = ref({})
const showPassword = ref(false)
const showConfirm = ref(false)
const socialLoading = ref(null)

const passwordStrength = computed(() => {
  const pwd = form.value.password
  if (!pwd) return { score: 0, label: '', color: '' }
  let score = 0
  if (pwd.length >= 8) score++
  if (/[A-Z]/.test(pwd)) score++
  if (/[0-9]/.test(pwd)) score++
  if (/[^A-Za-z0-9]/.test(pwd)) score++
  const levels = [
    { label: 'Too short', color: 'bg-red-500' },
    { label: 'Weak', color: 'bg-red-500' },
    { label: 'Fair', color: 'bg-yellow-500' },
    { label: 'Good', color: 'bg-blue-500' },
    { label: 'Strong', color: 'bg-green-500' },
  ]
  return { score, ...levels[score] }
})

const validate = () => {
  const e = {}
  if (!form.value.name.trim()) e.name = 'Name is required.'
  else if (form.value.name.length > 255) e.name = 'Name must be under 255 characters.'
  if (!form.value.email.trim()) e.email = 'Email is required.'
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) e.email = 'Enter a valid email address.'
  if (!form.value.password) e.password = 'Password is required.'
  else if (form.value.password.length < 8) e.password = 'Password must be at least 8 characters.'
  if (form.value.password !== form.value.password_confirmation)
    e.password_confirmation = 'Passwords do not match.'
  return e
}

const handleRegister = async () => {
  errors.value = {}
  message.value = ''

  const validationErrors = validate()
  if (Object.keys(validationErrors).length) {
    errors.value = validationErrors
    message.value = 'Please fix the highlighted fields.'
    message_type.value = 'error'
    return
  }

  loading.value = true

  try {
    const data = await post('/api/register', {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
    })

    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify({
      id: data.user?.id,
      name: data.user?.name,
      email: data.user?.email,
      role: data.role,
    }))

    message.value = 'Account created! Redirecting...'
    message_type.value = 'success'

    setTimeout(() => { router.push('/home') }, 900)
  } catch (err) {
    var errData = err.data || {}
    if (errData.errors) {
      errors.value = Object.fromEntries(
        Object.entries(errData.errors).map(([k, v]) => [k, Array.isArray(v) ? v[0] : v])
      )
    }
    message.value = errData.message || 'Registration failed. Please try again.'
    message_type.value = 'error'
  } finally {
    loading.value = false
  }
}

const handleSocialLogin = (provider) => {
  socialLoading.value = provider
  const apiUrl = import.meta.env.VITE_API_BASE_URL
  window.location.href = `${apiUrl}/api/auth/${provider}/redirect`
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
    <!-- Register Card -->
    <div class="w-full max-w-md">
      <!-- Logo/Header -->
      <div class="text-center mb-8">
        <div class="inline-block bg-white rounded-full p-2 shadow-sm">
          <img src="/images/logo.png" alt="" class="w-20" />
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mt-4 mb-1">Create your account</h1>
        <p class="text-gray-500">Join Online Shop as a customer</p>
      </div>

      <!-- Form Card -->
      <div class="bg-white rounded-2xl shadow-xl p-8 space-y-5">
        <!-- Message Alert -->
        <div
          v-if="message"
          :class="[
            'p-3.5 rounded-lg text-sm font-medium border',
            message_type === 'success'
              ? 'bg-green-50 text-green-700 border-green-200'
              : 'bg-red-50 text-red-700 border-red-200',
          ]"
        >
          {{ message }}
        </div>

        <!-- Name Field -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-user text-gray-400"></i>
            </div>
            <input
              v-model="form.name"
              type="text"
              placeholder="John Doe"
              :class="[
                'w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:border-transparent outline-none transition',
                errors.name ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-blue-400',
              ]"
            />
          </div>
          <p v-if="errors.name" class="mt-1.5 text-xs text-red-600">{{ errors.name }}</p>
        </div>

        <!-- Email Field -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input
              v-model="form.email"
              type="email"
              placeholder="john@example.com"
              :class="[
                'w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:border-transparent outline-none transition',
                errors.email ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-blue-400',
              ]"
            />
          </div>
          <p v-if="errors.email" class="mt-1.5 text-xs text-red-600">{{ errors.email }}</p>
        </div>

        <!-- Password Field -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="At least 8 characters"
              :class="[
                'w-full pl-10 pr-12 py-3 border rounded-lg focus:ring-2 focus:border-transparent outline-none transition',
                errors.password ? 'border-red-400 focus:ring-red-400' : 'border-gray-300 focus:ring-blue-400',
              ]"
            />
            <button
              @click="showPassword = !showPassword"
              type="button"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
            >
              <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </button>
          </div>
          <!-- Strength meter -->
          <div v-if="form.password" class="mt-2">
            <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
              <div
                class="h-full rounded-full transition-all duration-300"
                :class="passwordStrength.color"
                :style="{ width: `${(passwordStrength.score / 4) * 100}%` }"
              ></div>
            </div>
            <p class="mt-1 text-xs text-gray-500">Strength: {{ passwordStrength.label }}</p>
          </div>
          <p v-if="errors.password" class="mt-1.5 text-xs text-red-600">{{ errors.password }}</p>
        </div>

        <!-- Confirm Password Field -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input
              v-model="form.password_confirmation"
              :type="showConfirm ? 'text' : 'password'"
              placeholder="Re-enter your password"
              :class="[
                'w-full pl-10 pr-12 py-3 border rounded-lg focus:ring-2 focus:border-transparent outline-none transition',
                errors.password_confirmation
                  ? 'border-red-400 focus:ring-red-400'
                  : 'border-gray-300 focus:ring-blue-400',
              ]"
            />
            <button
              @click="showConfirm = !showConfirm"
              type="button"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
            >
              <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </button>
          </div>
          <p v-if="errors.password_confirmation" class="mt-1.5 text-xs text-red-600">
            {{ errors.password_confirmation }}
          </p>
        </div>

        <!-- Register Button -->
        <button
          @click="handleRegister"
          :disabled="loading"
          class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition disabled:opacity-60 disabled:cursor-not-allowed"
        >
          <span v-if="!loading">Create Account</span>
          <span v-else class="flex items-center justify-center">
            <svg
              class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
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
            Creating account...
          </span>
        </button>

        <!-- Divider -->
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or sign up with</span>
          </div>
        </div>

        <!-- Social Sign Up -->
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

        <!-- Login Link -->
        <p class="text-center text-gray-600">
          Already have an account?
          <router-link to="/login" class="text-blue-500 hover:text-blue-600 font-semibold">
            Sign in here
          </router-link>
        </p>
      </div>

      <!-- Footer -->
      <p class="text-center text-gray-500 text-sm mt-6">
        By creating an account, you agree to our
        <a href="#" class="underline hover:text-gray-900">Terms of Service</a>
        and
        <a href="#" class="underline hover:text-gray-900">Privacy Policy</a>
      </p>
    </div>
  </div>
</template>
