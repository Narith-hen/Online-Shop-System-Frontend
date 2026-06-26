<template>
  <div class="min-h-screen bg-gray-50">
    <!-- ==================== Mobile Overlay ==================== -->
    <transition name="fade">
      <div
        v-if="mobileOpen"
        @click="mobileOpen = false"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-30 lg:hidden"
      ></div>
    </transition>

    <!-- ==================== Sidebar ==================== -->
    <aside
      class="fixed lg:sticky top-0 left-0 z-40 h-screen bg-gray-900 text-gray-300 flex flex-col transition-all duration-300 ease-in-out lg:translate-x-0"
      :class="[
        collapsed ? 'lg:w-20' : 'lg:w-64',
        'w-72',
        mobileOpen ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <!-- Sidebar header (brand + close) -->
      <div
        class="flex items-center gap-3 h-16 px-4 border-b border-white/10 shrink-0"
      >
        <img src="/images/logo.png" alt="Online Shop" class="h-8 w-auto shrink-0" />
        <span
          v-if="!collapsed"
          class="text-lg font-semibold tracking-tight text-white truncate"
        >Online Shop</span>
        <!-- Close button (mobile/tablet only) -->
        <button
          @click="mobileOpen = false"
          class="ml-auto lg:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition"
          aria-label="Close sidebar"
        >
          <i class="fas fa-xmark text-lg"></i>
        </button>
      </div>

      <!-- Nav -->
      <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6">
        <div v-for="group in navGroups" :key="group.label">
          <p
            v-if="!collapsed"
            class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500"
          >{{ group.label }}</p>
          <div class="space-y-1">
            <template v-for="item in group.items" :key="item.name">
              <router-link
                v-if="item.vueRoute"
                :to="item.to"
                @click="mobileOpen = false"
                class="admin-nav-link"
                :class="{ 'justify-center': collapsed }"
                :title="collapsed ? item.name : ''"
              >
                <i :class="item.icon" class="w-5 text-center shrink-0"></i>
                <span v-if="!collapsed" class="truncate">{{ item.name }}</span>
              </router-link>
              <a
                v-else
                :href="item.to"
                target="_blank"
                class="admin-nav-link"
                :class="{ 'justify-center': collapsed }"
                :title="collapsed ? item.name : ''"
              >
                <i :class="item.icon" class="w-5 text-center shrink-0"></i>
                <span v-if="!collapsed" class="truncate">{{ item.name }}</span>
              </a>
            </template>
          </div>
        </div>
      </nav>

      <!-- Sidebar footer: user + logout -->
      <div class="border-t border-white/10 p-3 shrink-0">
        <div
          class="flex items-center gap-3 rounded-lg p-2"
          :class="{ 'justify-center': collapsed }"
        >
          <span
            class="w-9 h-9 rounded-full bg-gray-700 text-white text-sm font-semibold flex items-center justify-center shrink-0"
          >{{ initial }}</span>
          <div v-if="!collapsed" class="min-w-0">
            <p class="text-sm font-medium text-white truncate">{{ userName }}</p>
            <p class="text-xs text-gray-500 truncate">Administrator</p>
          </div>
        </div>
        <button
          @click="handleLogout"
          class="admin-nav-link mt-2 text-red-400 hover:text-red-300 hover:bg-red-500/10"
          :class="{ 'justify-center': collapsed }"
          :title="collapsed ? 'Logout' : ''"
        >
          <i class="fas fa-right-from-bracket w-5 text-center shrink-0"></i>
          <span v-if="!collapsed">Logout</span>
        </button>
      </div>
    </aside>

    <!-- ==================== Main Column ==================== -->
    <div class="lg:pl-0 flex flex-col min-h-screen">
      <!-- Topbar -->
      <header
        class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-gray-100"
      >
        <div class="flex items-center gap-3 h-16 px-4 sm:px-6">
          <!-- Sidebar toggle -->
          <button
            @click="toggleSidebar"
            class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
            aria-label="Toggle sidebar"
          >
            <i :class="mobileOpen ? 'fas fa-xmark' : 'fas fa-bars'" class="text-lg lg:hidden"></i>
            <i class="fas fa-bars text-lg hidden lg:block"></i>
          </button>

          <!-- Page title -->
          <div class="min-w-0">
            <h1 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
              {{ pageTitle }}
            </h1>
            <p class="hidden sm:block text-xs text-gray-500 truncate">
              Welcome back, {{ userName }}
            </p>
          </div>

          <!-- Search (tablet/desktop) -->
          <div class="hidden md:flex items-center ml-auto relative">
            <i class="fas fa-magnifying-glass absolute left-3 text-gray-400 text-sm"></i>
            <input
              type="text"
              placeholder="Search..."
              class="w-40 lg:w-64 pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition"
            />
          </div>

          <!-- Right actions -->
          <div class="flex items-center gap-1 sm:gap-2 ml-auto md:ml-3">
            <!-- Notifications -->
            <div class="relative">
              <button
                @click="notifOpen = !notifOpen"
                class="relative inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
                aria-label="Notifications"
              >
                <i class="fas fa-bell text-lg"></i>
                <span
                  class="absolute top-1 right-1 bg-red-500 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 px-1 flex items-center justify-center"
                >3</span>
              </button>
              <transition name="dropdown">
                <div
                  v-if="notifOpen"
                  class="absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-xl border border-gray-100 shadow-lg py-2 overflow-hidden"
                >
                  <div class="px-4 py-2 border-b border-gray-50 flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-900">Notifications</p>
                    <span class="text-xs text-gray-400">3 new</span>
                  </div>
                  <a href="#" @click.prevent class="notif-item">
                    <i class="fas fa-bag-shopping text-blue-500"></i>
                    <div class="min-w-0">
                      <p class="text-sm text-gray-800 truncate">New order #1042</p>
                      <p class="text-xs text-gray-400">2 minutes ago</p>
                    </div>
                  </a>
                  <a href="#" @click.prevent class="notif-item">
                    <i class="fas fa-user-plus text-green-500"></i>
                    <div class="min-w-0">
                      <p class="text-sm text-gray-800 truncate">New customer registered</p>
                      <p class="text-xs text-gray-400">1 hour ago</p>
                    </div>
                  </a>
                  <a href="#" @click.prevent class="notif-item">
                    <i class="fas fa-triangle-exclamation text-amber-500"></i>
                    <div class="min-w-0">
                      <p class="text-sm text-gray-800 truncate">Low stock: 2 products</p>
                      <p class="text-xs text-gray-400">3 hours ago</p>
                    </div>
                  </a>
                </div>
              </transition>
            </div>

            <!-- Profile dropdown -->
            <div class="relative">
              <button
                @click="profileOpen = !profileOpen"
                class="flex items-center gap-2 h-10 pl-1 pr-2 rounded-full hover:bg-gray-100 transition"
                aria-label="Profile menu"
              >
                <span
                  class="w-8 h-8 rounded-full bg-gray-900 text-white text-sm font-semibold flex items-center justify-center"
                >{{ initial }}</span>
                <span class="hidden sm:block text-sm font-medium text-gray-700 truncate max-w-[120px]">{{ userName }}</span>
                <i class="fas fa-chevron-down text-xs text-gray-400 hidden sm:block"></i>
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
                  <a href="#" @click.prevent class="dropdown-item">
                    <i class="fas fa-user w-5"></i> My Profile
                  </a>
                  <a href="#" @click.prevent class="dropdown-item">
                    <i class="fas fa-gear w-5"></i> Settings
                  </a>
                  <div class="border-t border-gray-50 my-1"></div>
                  <button @click="handleLogout" class="dropdown-item w-full text-left text-red-600">
                    <i class="fas fa-right-from-bracket w-5"></i> Logout
                  </button>
                </div>
              </transition>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8">
        <router-view v-slot="{ Component }">
          <transition name="route" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const mobileOpen = ref(false)
const collapsed = ref(false)
const profileOpen = ref(false)
const notifOpen = ref(false)

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
refreshAuth()

const userName = computed(() => authState.value.user.name || 'Admin')
const userEmail = computed(() => authState.value.user.email || '')
const initial = computed(() => (userName.value ? userName.value.charAt(0).toUpperCase() : 'A'))

const pageTitle = computed(() => (route.meta?.title ? route.meta.title : 'Dashboard'))

const apiBase = import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000'

const navGroups = [
  {
    label: 'Main',
    items: [
      { name: 'Dashboard', icon: 'fas fa-gauge-high', to: '/admin/dashboard', vueRoute: true }
    ]
  },
  {
    label: 'Management',
    items: [
      { name: 'Products', icon: 'fas fa-box', to: apiBase + '/admin/products' },
      { name: 'Categories', icon: 'fas fa-tags', to: apiBase + '/admin/categories' },
      { name: 'Orders', icon: 'fas fa-bag-shopping', to: apiBase + '/admin/orders' },
      { name: 'Users', icon: 'fas fa-users', to: apiBase + '/admin/users' }
    ]
  },
  {
    label: 'System',
    items: [
      { name: 'Settings', icon: 'fas fa-gear', to: apiBase + '/admin/settings' }
    ]
  }
]

const toggleSidebar = () => {
  if (window.innerWidth >= 1024) {
    collapsed.value = !collapsed.value
  } else {
    mobileOpen.value = !mobileOpen.value
  }
}

const closeDropdowns = (e) => {
  if (profileOpen.value && !e.target.closest('.relative')) profileOpen.value = false
  if (notifOpen.value && !e.target.closest('.relative')) notifOpen.value = false
}

const handleResize = () => {
  if (window.innerWidth >= 1024) mobileOpen.value = false
}

watch(
  () => route.fullPath,
  () => {
    refreshAuth()
    mobileOpen.value = false
    profileOpen.value = false
    notifOpen.value = false
  }
)

onMounted(() => {
  document.addEventListener('click', closeDropdowns)
  window.addEventListener('resize', handleResize)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', closeDropdowns)
  window.removeEventListener('resize', handleResize)
})

const handleLogout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  router.push('/login')
}
</script>

<style scoped>
.admin-nav-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.625rem 0.75rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #d1d5db;
  border-radius: 0.5rem;
  transition: background 0.15s ease, color 0.15s ease;
}
.admin-nav-link:hover {
  background: rgba(255, 255, 255, 0.08);
  color: #ffffff;
}
:deep(.router-link-active).admin-nav-link {
  background: rgba(255, 255, 255, 0.12);
  color: #ffffff;
}
:deep(.router-link-active).admin-nav-link::before {
  content: '';
  position: absolute;
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

.notif-item {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  padding: 0.625rem 1rem;
  transition: background 0.15s ease;
}
.notif-item:hover {
  background: #f9fafb;
}
.notif-item i {
  margin-top: 0.125rem;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
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
