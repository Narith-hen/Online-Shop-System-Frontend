<template>
  <div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 animate-fade-in-down">My Profile</h1>

    <div v-if="loading" class="text-center py-12">
      <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
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
            <span v-else class="text-5xl font-bold text-blue-500">{{ user.name ? user.name.charAt(0).toUpperCase() : '?' }}</span>
          </div>
          <h2 class="text-2xl font-bold text-gray-900">{{ user.name }}</h2>
          <p class="text-gray-500 mt-1">{{ user.email }}</p>
          <span class="inline-block mt-3 bg-blue-200 text-blue-500 text-sm font-semibold px-3 py-1 rounded-full capitalize">
            {{ user.role?.name || 'User' }}
          </span>
        </div>
      </div>

      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 animate-fade-in-up">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Edit Information</h3>
          <form @submit.prevent="updateProfile" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input v-model="form.email" type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                <input v-model="form.phone" type="tel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">User Code</label>
                <p class="text-gray-900 py-2">{{ user.code || 'N/A' }}</p>
              </div>
            </div>
            <button type="submit" :disabled="updating" class="bg-blue-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-600 transition disabled:opacity-50">
              <i v-if="updating" class="fas fa-spinner fa-spin mr-2"></i>
              {{ updating ? 'Saving...' : 'Save Changes' }}
            </button>
          </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6 animate-fade-in-up">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Account Details</h3>
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-gray-500">Member Since</span>
              <span class="font-semibold text-gray-900">{{ formatDate(user.created_at) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Email Verified</span>
              <span v-if="user.email_verified_at" class="text-green-600 font-semibold">Verified</span>
              <span v-else class="text-red-600 font-semibold">Not Verified</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 animate-fade-in-up">
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
              :class="notificationsEnabled ? 'bg-blue-500' : 'bg-gray-300'"
            >
              <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition" :class="notificationsEnabled ? 'left-6' : 'left-0.5'"></span>
            </button>
          </div>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow-md p-6 animate-fade-in-up">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Change Password</h3>
          <form @submit.prevent="changePassword" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Current Password</label>
              <input v-model="pwForm.current_password" type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400" required />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">New Password</label>
              <input v-model="pwForm.password" type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400" required minlength="8" />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm New Password</label>
              <input v-model="pwForm.password_confirmation" type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400" required />
            </div>
            <button type="submit" :disabled="changingPw" class="bg-blue-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-600 transition disabled:opacity-50">
              <i v-if="changingPw" class="fas fa-spinner fa-spin mr-2"></i>
              {{ changingPw ? 'Updating...' : 'Update Password' }}
            </button>
          </form>
        </div>
      </div>
    </div>
    <Toast ref="toastRef" />
  </div>
</template>

<script>
import Toast from '@/components/Toast.vue'
import { get, post } from '@/services/api'

export default {
  components: { Toast },
  name: 'UserProfile',
  data() {
    return {
      user: null,
      loading: true,
      updating: false,
      updateMessage: '',
      notificationsEnabled: true,
      togglingNotif: false,
      notifMessage: '',
      form: { name: '', email: '', phone: '' },
      pwForm: { current_password: '', password: '', password_confirmation: '' },
      changingPw: false,
      pwMessage: '',
      pwSuccess: false,
    }
  },
  async mounted() {
    await this.fetchProfile()
  },
  methods: {
    async fetchProfile() {
      this.loading = true
      try {
        const data = await get('/api/profile')
        this.user = data.user || data.data || null
        this.notificationsEnabled = this.user?.notifications_enabled !== false
        this.form.name = this.user?.name || ''
        this.form.email = this.user?.email || ''
        this.form.phone = this.user?.phone || ''
      } catch {
        this.user = null
      } finally {
        this.loading = false
      }
    },
    async updateProfile() {
      this.updating = true
      try {
        const data = await post('/api/profile', { name: this.form.name, email: this.form.email, phone: this.form.phone })
        this.user = data.user || data.data || this.user
        const stored = JSON.parse(localStorage.getItem('user') || '{}')
        stored.name = this.form.name; stored.email = this.form.email
        localStorage.setItem('user', JSON.stringify(stored))
        this.$refs.toastRef?.show({ type: 'success', title: 'Profile Updated', message: 'Your profile has been updated successfully.' })
      } catch (err) {
        const msg = err.data?.message || 'Failed to update profile.'
        this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: msg })
      } finally {
        this.updating = false
      }
    },
    async changePassword() {
      this.changingPw = true
      try {
        const data = await post('/api/change-password', {
          current_password: this.pwForm.current_password,
          password: this.pwForm.password,
          password_confirmation: this.pwForm.password_confirmation,
        })
        this.pwForm = { current_password: '', password: '', password_confirmation: '' }
        this.$refs.toastRef?.show({ type: 'success', title: 'Password Changed', message: data.message || 'Password changed successfully!' })
      } catch (err) {
        const msg = err.data?.message || 'Failed to change password.'
        this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: msg })
      } finally {
        this.changingPw = false
      }
    },
    formatDate(date) {
      if (!date) return 'N/A'
      return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
    },
    async toggleNotifications() {
      this.togglingNotif = true
      try {
        const data = await post('/api/notifications/toggle')
        this.notificationsEnabled = data.enabled
        this.$refs.toastRef?.show({ type: 'success', title: 'Updated', message: data.message || 'Notification preference updated.' })
      } catch {
        this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: 'Failed to update notification preference.' })
      } finally {
        this.togglingNotif = false
      }
    },
  },
}
</script>
