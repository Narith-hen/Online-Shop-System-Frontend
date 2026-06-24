import { createRouter, createWebHistory } from 'vue-router'

import Login from '@/components/Auth/Login.vue'
import dashboard from '@/views/admin/dashboard.vue'
import NotFound from '@/views/NotFound.vue'
import MainLayout from '@/components/layouts/MainLayout.vue'

// Customer Views
import CustomerHome from '@/views/customer/HomePage.vue'
import CustomerProduct from '@/views/customer/ProductPage.vue'
import CustomerAbout from '@/views/customer/AboutPage.vue'
import CustomerContact from '@/views/customer/ContactPage.vue'
import CustomerOrderCard from '@/views/customer/OrderCard.vue'

// Welcome
import WelcomePage from '@/views/WelcomePage.vue'

// Auth Callback
import AuthCallback from '@/components/Auth/AuthCallback.vue'

const routes = [
  {
    path: '/',
    name: 'Welcome',
    component: WelcomePage
  },

  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { guest: true }           // Only for non-logged in users
  },

  {
    path: '/auth/callback',
    name: 'AuthCallback',
    component: AuthCallback,
    meta: { guest: true }           // No auth required - this is where users land after OAuth
  },

  // ==================== ADMIN ROUTES ====================
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: dashboard,
    meta: { requiresAuth: true, role: 'admin' }
  },

  // ==================== CUSTOMER ROUTES ====================
  {
    path: '/',
    component: MainLayout,
    // redirect: '/customer/home',
    meta: { requiresAuth: true, role: 'customer' },
    children: [
      {
        path: 'home',
        name: 'CustomerHome',
        component: CustomerHome
      },
      {
        path: 'products',
        name: 'Products',
        component: CustomerProduct
      },
      {
        path: 'about',
        name: 'About',
        component: CustomerAbout
      },
      {
        path: 'contact',
        name: 'Contact',
        component: CustomerContact
      },
      {
        path: 'orders',
        name: 'Orders',
        component: CustomerOrderCard
      }
    ]
  },

  // 404
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// 🔐 Route Guard
router.beforeEach((to, from, next) => {
  if (to.path === '/login' && to.query.logout) {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    next()
    return
  }

  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  const userRole = user.role

  // Redirect logged-in users away from guest-only pages (e.g. /login)
  if (to.meta.guest && token) {
    if (userRole === 'admin') {
      next('/dashboard')
    } else {
      next('/home')
    }
    return
  }

  // Check protected routes
  if (to.meta.requiresAuth) {
    if (!token) {
      next('/login')
      return
    }

    // Role check — redirect to correct dashboard if role doesn't match
    if (to.meta.role && userRole !== to.meta.role) {
      if (userRole === 'admin') {
        next('/dashboard')
      } else {
        next('/home')
      }
      return
    }
  }

  next()
})

export default router