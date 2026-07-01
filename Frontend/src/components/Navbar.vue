<template>
  <nav class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2.5 shrink-0">
          <img src="/images/logo.png" alt="Online Shop" class="h-8 w-auto" />
          <span class="text-xl font-semibold tracking-tight text-gray-900">Online Shop</span>
        </router-link>

        <!-- Desktop Nav -->
        <div v-if="isLoggedIn && isCustomer" class="hidden md:flex items-center gap-1">
          <router-link to="/home" class="nav-link">Home</router-link>
          <router-link to="/products" class="nav-link">Products</router-link>
          <router-link to="/categories" class="nav-link">Categories</router-link>
          <router-link to="/wishlist" class="nav-link">Wishlist</router-link>
          <router-link to="/orders" class="nav-link">Orders</router-link>
          <router-link to="/about" class="nav-link">About</router-link>
          <router-link to="/contact" class="nav-link">Contact</router-link>
        </div>
        <div v-else-if="!isLoggedIn" class="hidden md:flex items-center gap-1">
          <a href="#features" class="nav-link">Features</a>
          <a href="#testimonials" class="nav-link">Testimonials</a>
          <a href="#pricing" class="nav-link">Pricing</a>
          <a href="#contact" class="nav-link">Contact</a>
        </div>

        <!-- Right side -->
        <div class="flex items-center gap-1 sm:gap-2">
          <template v-if="!isLoggedIn">
            <router-link to="/login" class="btn-primary text-sm py-2 px-4">
              <i class="fas fa-arrow-right-to-bracket"></i>
              Login
            </router-link>
          </template>

          <template v-else-if="isCustomer">
            <!-- Cart (visible on all screens) -->
            <router-link to="/cart"
              class="relative w-10 h-10 rounded-full flex items-center justify-center text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
              aria-label="Cart">
              <i class="fas fa-shopping-bag text-lg"></i>
              <span v-if="cartCount > 0"
                class="absolute -top-0.5 -right-0.5 bg-gray-900 text-white text-[10px] font-semibold rounded-full min-w-[18px] h-[18px] px-1 flex items-center justify-center animate-scale-in">
                {{ cartCount > 99 ? '99+' : cartCount }}
              </span>
            </router-link>

            <!-- Notifications & Profile (desktop only) -->
            <div class="hidden sm:flex items-center gap-1">
              <div class="relative" ref="notifContainer">
                <button @click.stop="notifOpen = !notifOpen"
                  class="relative w-10 h-10 rounded-full flex items-center justify-center text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
                  aria-label="Notifications">
                  <i class="fas fa-bell text-lg"></i>
                  <span v-if="unreadCount > 0"
                    class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-semibold rounded-full min-w-[18px] h-[18px] px-1 flex items-center justify-center animate-scale-in">
                    {{ unreadCount > 99 ? '99+' : unreadCount }}
                  </span>
                </button>
                <transition name="dropdown">
                  <div v-if="notifOpen"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-xl border border-gray-100 shadow-lg py-2 max-h-96 overflow-y-auto origin-top-right">
                    <div class="px-4 py-2 border-b border-gray-50 flex justify-between items-center">
                      <p class="text-sm font-semibold text-gray-900">Notifications</p>
                      <button v-if="unreadCount > 0" @click="markAllRead"
                        class="text-xs text-blue-500 hover:text-blue-600 font-semibold">Mark all read</button>
                    </div>
                    <div v-if="notifications.length === 0" class="px-4 py-8 text-center text-sm text-gray-400">
                      <i class="fas fa-bell text-2xl mb-2 block text-gray-300"></i>
                      No notifications yet.
                    </div>
                    <div v-else>
                      <div v-for="n in notifications" :key="n.id" @click="handleNotifClick(n)"
                        class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition" :class="!n.read ? 'bg-blue-100/50' : ''">
                        <div class="flex gap-3">
                          <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-sm" :class="notifIconClass(n.type)">
                            <i :class="notifIcon(n.type)"></i>
                          </div>
                          <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ n.title }}</p>
                            <p class="text-xs text-gray-500 line-clamp-2">{{ n.message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ timeAgo(n.created_at) }}</p>
                          </div>
                          <div v-if="!n.read" class="w-2 h-2 bg-blue-500 rounded-full shrink-0 mt-2"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </transition>
              </div>

              <div class="relative">
                <button @click="profileOpen = !profileOpen"
                  class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition"
                  aria-label="Profile menu">
                  <span class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 text-white text-sm font-semibold flex items-center justify-center">
                    {{ userInitial }}
                  </span>
                </button>
                <transition name="dropdown">
                  <div v-if="profileOpen"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl border border-gray-100 shadow-lg py-2 overflow-hidden origin-top-right">
                    <div class="px-4 py-3 border-b border-gray-50">
                      <p class="text-sm font-semibold text-gray-900 truncate">{{ userName }}</p>
                      <p class="text-xs text-gray-500 truncate">{{ userEmail }}</p>
                    </div>
                    <router-link to="/profile" @click="profileOpen = false" class="dropdown-item">
                      <i class="fas fa-user w-5 text-gray-400"></i> My Profile
                    </router-link>
                    <router-link to="/orders" @click="profileOpen = false" class="dropdown-item">
                      <i class="fas fa-bag-shopping w-5 text-gray-400"></i> My Orders
                    </router-link>
                    <router-link to="/cart" @click="profileOpen = false" class="dropdown-item">
                      <i class="fas fa-cart-shopping w-5 text-gray-400"></i> Shopping Cart
                    </router-link>
                    <div class="border-t border-gray-50 my-1"></div>
                    <button @click="handleLogout" class="dropdown-item w-full text-left text-red-600">
                      <i class="fas fa-right-from-bracket w-5"></i> Logout
                    </button>
                  </div>
                </transition>
              </div>
            </div>
          </template>

          <!-- Mobile hamburger -->
          <button @click="mobileOpen = !mobileOpen"
            class="md:hidden w-10 h-10 rounded-full flex items-center justify-center text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
            aria-label="Menu">
            <i :class="mobileOpen ? 'fas fa-xmark' : 'fas fa-bars'" class="text-lg"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile drawer overlay -->
    <div v-if="mobileOpen" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[55] md:hidden" @click="mobileOpen = false"></div>

    <!-- Mobile drawer -->
    <div v-if="mobileOpen" class="fixed top-0 left-0 h-full w-72 bg-white shadow-2xl z-[60] md:hidden flex flex-col">
      <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 shrink-0">
          <div class="flex items-center gap-2.5">
            <img src="/images/logo.png" alt="Online Shop" class="h-8 w-auto" />
            <span class="font-semibold text-gray-900 text-lg">Menu</span>
          </div>
          <button @click="mobileOpen = false" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
            <i class="fas fa-xmark text-lg"></i>
          </button>
        </div>

        <template v-if="isLoggedIn && isCustomer">
          <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 shrink-0">
            <div class="flex items-center gap-3">
              <span class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 text-white text-base font-semibold flex items-center justify-center shrink-0">{{ userInitial }}</span>
              <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ userName }}</p>
                <p class="text-xs text-gray-500 truncate">{{ userEmail }}</p>
              </div>
            </div>
          </div>
          <div class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
            <router-link to="/home" @click="mobileOpen = false" class="mobile-link" active-class="mobile-link-active">
              <i class="fas fa-home w-6 text-gray-400"></i> Home
            </router-link>
            <router-link to="/products" @click="mobileOpen = false" class="mobile-link" active-class="mobile-link-active">
              <i class="fas fa-box w-6 text-gray-400"></i> Products
            </router-link>
            <router-link to="/categories" @click="mobileOpen = false" class="mobile-link" active-class="mobile-link-active">
              <i class="fas fa-tag w-6 text-gray-400"></i> Categories
            </router-link>
            <router-link to="/wishlist" @click="mobileOpen = false" class="mobile-link" active-class="mobile-link-active">
              <i class="fas fa-heart w-6 text-gray-400"></i> Wishlist
            </router-link>
            <router-link to="/orders" @click="mobileOpen = false" class="mobile-link" active-class="mobile-link-active">
              <i class="fas fa-receipt w-6 text-gray-400"></i> Orders
            </router-link>
            <router-link to="/about" @click="mobileOpen = false" class="mobile-link" active-class="mobile-link-active">
              <i class="fas fa-info-circle w-6 text-gray-400"></i> About
            </router-link>
            <router-link to="/contact" @click="mobileOpen = false" class="mobile-link" active-class="mobile-link-active">
              <i class="fas fa-envelope w-6 text-gray-400"></i> Contact
            </router-link>
            <hr class="my-3 border-gray-100" />
            <router-link to="/profile" @click="mobileOpen = false" class="mobile-link" active-class="mobile-link-active">
              <i class="fas fa-user w-6 text-gray-400"></i> My Profile
            </router-link>
            <router-link to="/cart" @click="mobileOpen = false" class="mobile-link" active-class="mobile-link-active">
              <i class="fas fa-shopping-bag w-6 text-gray-400"></i> Cart
              <span v-if="cartCount > 0" class="ml-auto bg-gray-900 text-white text-xs font-semibold px-2 py-0.5 rounded-full min-w-[22px] text-center">{{ cartCount }}</span>
            </router-link>
          </div>
          <div class="border-t border-gray-100 p-3 shrink-0">
            <button @click="handleLogout" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition text-sm font-medium">
              <i class="fas fa-right-from-bracket w-6"></i> Logout
            </button>
          </div>
        </template>
        <template v-else-if="!isLoggedIn">
          <div class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
            <a href="#features" @click="mobileOpen = false" class="mobile-link">
              <i class="fas fa-star w-6 text-gray-400"></i> Features
            </a>
            <a href="#testimonials" @click="mobileOpen = false" class="mobile-link">
              <i class="fas fa-quote-right w-6 text-gray-400"></i> Testimonials
            </a>
            <a href="#pricing" @click="mobileOpen = false" class="mobile-link">
              <i class="fas fa-tag w-6 text-gray-400"></i> Pricing
            </a>
            <a href="#contact" @click="mobileOpen = false" class="mobile-link">
              <i class="fas fa-envelope w-6 text-gray-400"></i> Contact
            </a>
            <hr class="my-3 border-gray-100" />
            <router-link to="/login" @click="mobileOpen = false" class="mobile-link text-blue-500 font-semibold">
              <i class="fas fa-arrow-right-to-bracket w-6"></i> Login
            </router-link>
          </div>
        </template>
    </div>
  </nav>
  <Toast ref="toastRef" />
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import Toast from '@/components/Toast.vue'
import { useAuth } from '@/stores/auth'
import { useSocket } from '@/composables/useSocket'
import { get, post } from '@/services/api'

const router = useRouter()
const route = useRoute()
const { isLoggedIn, isCustomer, userName, userEmail, userInitial, clearAuth, refresh } = useAuth()
const { connect: connectSocket, disconnect: disconnectSocket, on: socketOn, off: socketOff } = useSocket()

const cartCount = ref(0)
const profileOpen = ref(false)
const mobileOpen = ref(false)
const notifications = ref([])
const unreadCount = ref(0)
const notifOpen = ref(false)
const notifContainer = ref(null)
const toastRef = ref(null)

watch(() => route.fullPath, () => {
  refresh()
  if (isCustomer.value) { fetchCartCount(); fetchNotifications() }
  profileOpen.value = false
  mobileOpen.value = false
})

onMounted(() => {
  if (isCustomer.value) { fetchCartCount(); fetchNotifications(); initSocket() }
  document.addEventListener('click', closeDropdowns)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', closeDropdowns)
  disconnectSocket()
})

function initSocket() {
  const socket = connectSocket()
  if (!socket) return
  socketOn('notification', (data) => {
    if (data && data.id) {
      notifications.value.unshift({
        id: data.id, title: data.title, message: data.message,
        type: data.type, link: data.link, created_at: data.created_at, read: false,
      })
      unreadCount.value++
      if (toastRef.value) toastRef.value.show(data)
    }
  })
  socketOn('cart-update', () => { if (isCustomer.value) fetchCartCount() })
}

const closeDropdowns = (e) => {
  var target = e.target
  if (!target || !target.closest) return
  if (profileOpen.value && !target.closest('.relative')) profileOpen.value = false
  if (notifOpen.value && notifContainer.value && !notifContainer.value.contains(target)) notifOpen.value = false
}

const fetchCartCount = async () => {
  try { const data = await get('/api/cart'); cartCount.value = data.count || 0 } catch {}
}

const fetchNotifications = async () => {
  try {
    const data = await get('/api/notifications')
    notifications.value = data.data || []
    unreadCount.value = data.unread_count || 0
  } catch {}
}

const markAllRead = async () => {
  try {
    await post('/api/notifications/read-all')
    notifications.value.forEach(n => { n.read = true })
    unreadCount.value = 0
  } catch {}
}

const handleNotifClick = async (n) => {
  if (!n.read) {
    try {
      await post(`/api/notifications/${n.id}/read`)
      n.read = true
      unreadCount.value = Math.max(0, unreadCount.value - 1)
    } catch {}
  }
  notifOpen.value = false
  if (n.link) router.push(n.link)
}

const notifIcon = (type) => {
  const icons = { new_product: 'fas fa-box', news: 'fas fa-newspaper', promotion: 'fas fa-tag' }
  return icons[type] || 'fas fa-bell'
}

const notifIconClass = (type) => {
  const classes = { new_product: 'bg-blue-200 text-blue-500', news: 'bg-purple-100 text-purple-600', promotion: 'bg-green-100 text-green-600' }
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
  clearAuth()
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
.nav-link:hover { color: #111827; background: #f9fafb; }
.nav-link.router-link-active { color: #111827; font-weight: 600; }
.nav-link.router-link-active::after {
  content: ''; position: absolute; left: 0.875rem; right: 0.875rem;
  bottom: 0.125rem; height: 2px; background: #111827; border-radius: 2px;
}

.mobile-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 0.875rem;
  font-size: 0.9rem;
  font-weight: 500;
  color: #374151;
  border-radius: 0.5rem;
  transition: all 0.15s ease;
}
.mobile-link:hover { background: #f3f4f6; color: #111827; }
.mobile-link-active { background: #eef2ff; color: #4f46e5; font-weight: 600; }
.mobile-link-active i { color: #4f46e5; }

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  color: #374151;
  transition: background 0.15s ease, color 0.15s ease;
}
.dropdown-item:hover { background: #f9fafb; color: #111827; }

.dropdown-enter-active, .dropdown-leave-active { transition: opacity 0.18s ease, transform 0.18s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px) scale(0.98); }

.drawer-enter-active, .drawer-leave-active { transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
.drawer-enter-from { transform: translateX(-100%); }
.drawer-leave-to { transform: translateX(-100%); }
.drawer-overlay-enter-active, .drawer-overlay-leave-active { transition: opacity 0.3s ease; }
.drawer-overlay-enter-from, .drawer-overlay-leave-to { opacity: 0; }
</style>
