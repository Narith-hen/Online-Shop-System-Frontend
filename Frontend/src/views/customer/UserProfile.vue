<template>
  <div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 animate-fade-in-down">My Profile</h1>

    <div v-if="loading" class="text-center py-12">
      <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
      <p class="text-gray-500">Loading profile...</p>
    </div>

    <div v-else-if="!user" class="text-center py-16">
      <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
        <i class="fas fa-user text-4xl text-gray-400"></i>
      </div>
      <h3 class="text-xl font-bold text-gray-900 mb-2">User Not Found</h3>
      <p class="text-gray-500">Unable to load profile information.</p>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-1 animate-fade-in-left">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
          <div class="w-32 h-32 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
            <img v-if="user.avatar_url" :src="user.avatar_url" :alt="user.name" class="w-32 h-32 rounded-full object-cover" />
            <span v-else class="text-5xl font-bold text-blue-600">{{ user.name ? user.name.charAt(0).toUpperCase() : '?' }}</span>
          </div>
          <h2 class="text-2xl font-bold text-gray-900">{{ user.name }}</h2>
          <p class="text-gray-500 mt-1">{{ user.email }}</p>
          <span class="inline-block mt-3 bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full capitalize">
            {{ user.role?.name || 'User' }}
          </span>
        </div>
      </div>

      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 animate-fade-in-up" style="animation-delay: 0ms">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Account Information</h3>
          <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">User Code</label>
                <p class="text-gray-900">{{ user.code || 'N/A' }}</p>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <p class="text-gray-900">{{ user.email }}</p>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Member Since</label>
                <p class="text-gray-900">{{ formatDate(user.created_at) }}</p>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email Verified</label>
                <p class="text-gray-900">
                  <span v-if="user.email_verified_at" class="text-green-600">Verified</span>
                  <span v-else class="text-red-600">Not Verified</span>
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 animate-fade-in-up" style="animation-delay: 120ms">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Notification Preferences</h3>
          <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
            <div>
              <p class="font-semibold text-gray-900">Push Notifications</p>
              <p class="text-sm text-gray-500">Get alerts about new products and news</p>
            </div>
            <button
              @click="toggleNotifications"
              :disabled="togglingNotif"
              class="relative w-12 h-6 rounded-full transition"
              :class="notificationsEnabled ? 'bg-blue-600' : 'bg-gray-300'"
            >
              <span
                class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition"
                :class="notificationsEnabled ? 'left-6' : 'left-0.5'"
              ></span>
            </button>
          </div>
          <p v-if="notifMessage" class="mt-2 text-sm" :class="notifMessage.includes('enabled') ? 'text-green-600' : 'text-gray-500'">
            {{ notifMessage }}
          </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 animate-fade-in-up" style="animation-delay: 240ms">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Links</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <router-link to="/orders" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:border-blue-600 hover:bg-blue-50 transition">
              <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-blue-600"></i>
              </div>
              <div>
                <p class="font-semibold text-gray-900">My Orders</p>
                <p class="text-sm text-gray-500">View your order history</p>
              </div>
            </router-link>
            <router-link to="/products" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:border-blue-600 hover:bg-blue-50 transition">
              <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-blue-600"></i>
              </div>
              <div>
                <p class="font-semibold text-gray-900">Browse Products</p>
                <p class="text-sm text-gray-500">Continue shopping</p>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserProfile',
  data() {
    return {
      user: null,
      loading: true,
      notificationsEnabled: true,
      togglingNotif: false,
      notifMessage: '',
    }
  },
  async mounted() {
    await this.fetchProfile()
  },
  methods: {
    async fetchProfile() {
      this.loading = true
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/profile`, {
          headers: { Authorization: `Bearer ${token}` },
        })
        if (res.ok) {
          const data = await res.json()
          this.user = data.user || data.data || null
          this.notificationsEnabled = this.user?.notifications_enabled !== false
        }
      } catch {
        this.user = null
      } finally {
        this.loading = false
      }
    },
    formatDate(date) {
      if (!date) return 'N/A'
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric',
      })
    },
    async toggleNotifications() {
      this.togglingNotif = true
      this.notifMessage = ''
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/notifications/toggle`, {
          method: 'POST',
          headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json' },
        })
        if (res.ok) {
          const data = await res.json()
          this.notificationsEnabled = data.enabled
          this.notifMessage = data.message
        }
      } catch {
        this.notifMessage = 'Failed to update preference.'
      } finally {
        this.togglingNotif = false
      }
    },
  },
}
</script>
