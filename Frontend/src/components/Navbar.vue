<template>
  <nav class="bg-white/90 backdrop-blur border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2.5 shrink-0">
          <img src="/images/logo.png" alt="Online Shop" class="h-8 w-auto" />
          <span class="text-xl font-semibold tracking-tight text-gray-900">Online Shop</span>
        </router-link>

        <!-- Navigation Links (customer only) -->
        <div v-if="isLoggedIn && isCustomer" class="hidden md:flex items-center gap-1">
          <router-link to="/home" class="nav-link">Home</router-link>
          <router-link to="/products" class="nav-link">Products</router-link>
          <router-link to="/categories" class="nav-link">Categories</router-link>
          <router-link to="/orders" class="nav-link">Orders</router-link>
          <router-link to="/about" class="nav-link">About</router-link>
          <router-link to="/contact" class="nav-link">Contact</router-link>
        </div>

        <!-- Welcome page nav links (guest) -->
        <div v-else-if="!isLoggedIn" class="hidden md:flex items-center gap-1">
          <a href="#features" class="nav-link">Features</a>
          <a href="#testimonials" class="nav-link">Testimonials</a>
          <a href="#pricing" class="nav-link">Pricing</a>
          <a href="#contact" class="nav-link">Contact</a>
        </div>

        <!-- Right side: auth actions -->
        <div class="flex items-center gap-2 sm:gap-3">
          <!-- Guest: show Login button -->
          <template v-if="!isLoggedIn">
            <router-link
              to="/login"
              class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium transition px-3 py-2 text-sm"
            >
              <i class="fas fa-arrow-right-to-bracket"></i>
              Login
            </router-link>
          </template>

          <!-- Logged-in customer: notifications + cart + profile -->
          <template v-else-if="isCustomer">
            <!-- Notification Bell -->
            <div class="relative" ref="notifContainer">
              <button
                @click.stop="notifOpen = !notifOpen"
                class="relative inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
                aria-label="Notifications"
              >
                <i class="fas fa-bell text-lg"></i>
                <span
                  v-if="unreadCount > 0"
                  class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-semibold rounded-full min-w-[18px] h-[18px] px-1 flex items-center justify-center"
                >{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
              </button>
              <transition name="dropdown">
                <div
                  v-if="notifOpen"
                  class="absolute right-0 mt-2 w-80 bg-white rounded-xl border border-gray-100 shadow-lg py-2 max-h-96 overflow-y-auto"
                >
                  <div class="px-4 py-2 border-b border-gray-50 flex justify-between items-center">
                    <p class="text-sm font-semibold text-gray-900">Notifications</p>
                    <button
                      v-if="unreadCount > 0"
                      @click="markAllRead"
                      class="text-xs text-blue-600 hover:text-blue-800 font-semibold"
                    >Mark all read</button>
                  </div>
                  <div v-if="notifications.length === 0" class="px-4 py-8 text-center text-sm text-gray-400">
                    No notifications yet.
                  </div>
                  <div v-else>
                    <div
                      v-for="n in notifications"
                      :key="n.id"
                      @click="handleNotifClick(n)"
                      class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition"
                      :class="!n.read ? 'bg-blue-50/50' : ''"
                    >
                      <div class="flex gap-3">
                        <div
                          class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-sm"
                          :class="notifIconClass(n.type)"
                        >
                          <i :class="notifIcon(n.type)"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                          <p class="text-sm font-semibold text-gray-900 truncate">{{ n.title }}</p>
                          <p class="text-xs text-gray-500 line-clamp-2">{{ n.message }}</p>
                          <p class="text-xs text-gray-400 mt-1">{{ timeAgo(n.created_at) }}</p>
                        </div>
                        <div v-if="!n.read" class="w-2 h-2 bg-blue-600 rounded-full shrink-0 mt-2"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </transition>
            </div>

            <!-- Cart Icon -->
            <router-link
              to="/cart"
              class="relative inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
              aria-label="Cart"
            >
              <i class="fas fa-shopping-bag text-lg"></i>
              <span
                v-if="cartCount > 0"
                class="absolute -top-0.5 -right-0.5 bg-gray-900 text-white text-[10px] font-semibold rounded-full min-w-[18px] h-[18px] px-1 flex items-center justify-center"
              >{{ cartCount > 99 ? '99+' : cartCount }}</span>
            </router-link>

            <!-- Profile Dropdown -->
            <div class="relative">
              <button
                @click="profileOpen = !profileOpen"
                class="flex items-center gap-2 w-10 h-10 rounded-full hover:bg-gray-100 transition"
                aria-label="Profile menu"
              >
                <span
                  class="w-8 h-8 rounded-full bg-gray-900 text-white text-sm font-semibold flex items-center justify-center"
                >
                  {{ initial }}
                </span>
              </button>
              <transition name="dropdown">
                <div
                  v-if="profileOpen"
                  class="absolute right-0 mt-2 w-56 bg-white rounded-xl border border-gray-100 shadow-lg py-2 overflow-hidden"
                >
                  <div class="px-4 py-2 border-b border-gray-50">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ userName }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ userEmail }}</p>
                  </div>
                  <router-link to="/profile" @click="profileOpen = false" class="dropdown-item">
                    <i class="fas fa-user w-5"></i> My Profile
                  </router-link>
                  <router-link to="/orders" @click="profileOpen = false" class="dropdown-item">
                    <i class="fas fa-bag-shopping w-5"></i> My Orders
                  </router-link>
                  <router-link to="/cart" @click="profileOpen = false" class="dropdown-item">
                    <i class="fas fa-cart-shopping w-5"></i> Shopping Cart
                  </router-link>
                  <div class="border-t border-gray-50 my-1"></div>
                  <button @click="handleLogout" class="dropdown-item w-full text-left text-red-600">
                    <i class="fas fa-right-from-bracket w-5"></i> Logout
                  </button>
                </div>
              </transition>
            </div>
          </template>

          <!-- Mobile menu toggle -->
          <button
            @click="mobileOpen = !mobileOpen"
            class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
            aria-label="Menu"
          >
            <i :class="mobileOpen ? 'fas fa-xmark' : 'fas fa-bars'" class="text-lg"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu panel -->
    <transition name="dropdown">
      <div v-if="mobileOpen" class="md:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1">
          <!-- Customer mobile links -->
          <template v-if="isLoggedIn && isCustomer">
            <router-link to="/home" @click="mobileOpen = false" class="mobile-link">Home</router-link>
            <router-link to="/products" @click="mobileOpen = false" class="mobile-link">Products</router-link>
            <router-link to="/categories" @click="mobileOpen = false" class="mobile-link">Categories</router-link>
            <router-link to="/orders" @click="mobileOpen = false" class="mobile-link">Orders</router-link>
            <router-link to="/about" @click="mobileOpen = false" class="mobile-link">About</router-link>
            <router-link to="/contact" @click="mobileOpen = false" class="mobile-link">Contact</router-link>
          </template>
          <!-- Guest mobile links -->
          <template v-else-if="!isLoggedIn">
            <a href="#features" @click="mobileOpen = false" class="mobile-link">Features</a>
            <a href="#testimonials" @click="mobileOpen = false" class="mobile-link">Testimonials</a>
            <a href="#pricing" @click="mobileOpen = false" class="mobile-link">Pricing</a>
            <a href="#contact" @click="mobileOpen = false" class="mobile-link">Contact</a>
          </template>
        </div>
      </div>
    </transition>
  </nav>
  <Toast ref="toastRef" />
</template>

<script setup>
import { computed, ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import Toast from '@/components/Toast.vue'
import { io } from 'socket.io-client'

const router = useRouter()
const route = useRoute()

const authState = ref({ token: null, user: {} })
const cartCount = ref(0)
const profileOpen = ref(false)
const mobileOpen = ref(false)
const notifications = ref([])
const unreadCount = ref(0)
const notifOpen = ref(false)
const notifContainer = ref(null)
let socket = null
const toastRef = ref(null)

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

refreshAuth()

const isLoggedIn = computed(() => !!authState.value.token)
const isCustomer = computed(() => authState.value.user.role === 'customer')
const userName = computed(() => authState.value.user.name || 'Customer')
const userEmail = computed(() => authState.value.user.email || '')
const initial = computed(() => (userName.value ? userName.value.charAt(0).toUpperCase() : '?'))

watch(
  () => route.fullPath,
  () => {
    refreshAuth()
    if (isCustomer.value) { fetchCartCount(); fetchNotifications() }
    profileOpen.value = false
    mobileOpen.value = false
  }
)

const connectSocket = () => {
  if (socket) return
  const wsUrl = import.meta.env.VITE_SOCKET_URL || 'http://localhost:3001'
  console.log('Connecting to socket at', wsUrl)
  socket = io(wsUrl, { transports: ['websocket', 'polling'] })
  socket.on('connect', () => console.log('Socket connected'))
  socket.on('connect_error', (err) => console.error('Socket error:', err.message))
  socket.on('notification', (data) => {
    if (data && data.id && (!data.user_id || data.user_id === authState.value.user.id)) {
      notifications.value.unshift({
        id: data.id,
        title: data.title,
        message: data.message,
        type: data.type,
        link: data.link,
        created_at: data.created_at,
        read: false,
      })
      unreadCount.value++
      if (data.type === 'new_product' && toastRef.value) {
        toastRef.value.show(data)
      }
    }
  })
  socket.on('cart-update', () => {
    if (isCustomer.value) fetchCartCount()
  })
  socket.on('disconnect', () => console.log('Socket disconnected'))
}

onMounted(() => {
  if (isCustomer.value) { fetchCartCount(); fetchNotifications(); connectSocket() }
  document.addEventListener('click', closeDropdowns)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', closeDropdowns)
  if (socket) { socket.disconnect(); socket = null }
})

const closeDropdowns = (e) => {
  if (profileOpen.value && !e.target.closest('.relative')) {
    profileOpen.value = false
  }
  if (notifOpen.value && notifContainer.value && !notifContainer.value.contains(e.target)) {
    notifOpen.value = false
  }
}

const fetchCartCount = async () => {
  try {
    const token = localStorage.getItem('token')
    if (!token) return
    const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/cart`, {
      headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
    })
    if (res.ok) {
      const data = await res.json()
      cartCount.value = data.count || 0
    }
  } catch {
    // ignore
  }
}

const fetchNotifications = async () => {
  try {
    const token = localStorage.getItem('token')
    if (!token) return
    const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/notifications`, {
      headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
    })
    if (res.ok) {
      const data = await res.json()
      notifications.value = data.data || []
      unreadCount.value = data.unread_count || 0
    }
  } catch { /* ignore */ }
}

const markAllRead = async () => {
  try {
    const token = localStorage.getItem('token')
    await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/notifications/read-all`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json', Accept: 'application/json' },
    })
    notifications.value.forEach(n => { n.read = true })
    unreadCount.value = 0
  } catch { /* ignore */ }
}

const handleNotifClick = async (n) => {
  if (!n.read) {
    try {
      const token = localStorage.getItem('token')
      await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/notifications/${n.id}/read`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json', Accept: 'application/json' },
      })
      n.read = true
      unreadCount.value = Math.max(0, unreadCount.value - 1)
    } catch { /* ignore */ }
  }
  notifOpen.value = false
  if (n.link) router.push(n.link)
}

const notifIcon = (type) => {
  const icons = { new_product: 'fas fa-box', news: 'fas fa-newspaper', promotion: 'fas fa-tag' }
  return icons[type] || 'fas fa-bell'
}

const notifIconClass = (type) => {
  const classes = { new_product: 'bg-blue-100 text-blue-600', news: 'bg-purple-100 text-purple-600', promotion: 'bg-green-100 text-green-600' }
  return classes[type] || 'bg-gray-100 text-gray-600'
}

const timeAgo = (date) => {
  const diff = Date.now() - new Date(date).getTime()
  const mins = Math.floor(diff / 60000)
  if (mins < 1) return 'Just now'
  if (mins < 60) return `${mins}m ago`
  const hrs = Math.floor(mins / 60)
  if (hrs < 24) return `${hrs}h ago`
  const days = Math.floor(hrs / 24)
  return `${days}d ago`
}

const handleLogout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  refreshAuth()
  cartCount.value = 0
  notifications.value = []
  unreadCount.value = 0
  profileOpen.value = false
  mobileOpen.value = false
  router.push('/login')
}
</script>

<style scoped>
.nav-link {
  position: relative;
  padding: 0.5rem 0.875rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #4b5563;
  transition: color 0.2s ease;
  border-radius: 0.5rem;
}
.nav-link:hover {
  color: #111827;
}
:deep(.router-link-active).nav-link {
  color: #111827;
  font-weight: 600;
}
:deep(.router-link-active).nav-link::after {
  content: '';
  position: absolute;
  left: 0.875rem;
  right: 0.875rem;
  bottom: 0.125rem;
  height: 2px;
  background: #111827;
  border-radius: 2px;
}

.mobile-link {
  display: block;
  padding: 0.625rem 0.75rem;
  font-size: 0.9rem;
  font-weight: 500;
  color: #374151;
  border-radius: 0.5rem;
  transition: background 0.2s ease, color 0.2s ease;
}
.mobile-link:hover {
  background: #f3f4f6;
  color: #111827;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  color: #374151;
  transition: background 0.15s ease, color 0.15s ease;
}
.dropdown-item:hover {
  background: #f9fafb;
  color: #111827;
}

.dropdown-enter-active,
.dropdown-leave-active {
  transition: opacity 0.18s ease, transform 0.18s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
