import { createRouter, createWebHistory } from 'vue-router'

import Login from '@/components/Auth/Login.vue'
import Register from '@/components/Auth/Register.vue'
import dashboard from '@/views/admin/dashboard.vue'
import NotFound from '@/views/NotFound.vue'
import AdminLayout from '@/components/layouts/AdminLayout.vue'
import MainLayout from '@/components/layouts/MainLayout.vue'

// Customer Views
import CustomerHome from '@/views/customer/HomePage.vue'
import CustomerProduct from '@/views/customer/ProductPage.vue'
import CustomerAbout from '@/views/customer/AboutPage.vue'
import CustomerContact from '@/views/customer/ContactPage.vue'
import CustomerOrderCard from '@/views/customer/OrderCard.vue'
import ProductDetail from '@/views/customer/ProductDetail.vue'
import CategoryPage from '@/views/customer/CategoryPage.vue'
import UserProfile from '@/views/customer/UserProfile.vue'
import CartPage from '@/views/customer/CartPage.vue'
import CheckoutPage from '@/views/customer/CheckoutPage.vue'
import ReceiptPage from '@/views/customer/ReceiptPage.vue'

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
    path: '/register',
    name: 'Register',
    component: Register,
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
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      {
        path: '',
        redirect: { name: 'AdminDashboard' }
      },
      {
        path: 'dashboard',
        name: 'AdminDashboard',
        component: dashboard,
        meta: { title: 'Dashboard' }
      },
    ]
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
        path: 'products/:id',
        name: 'ProductDetail',
        component: ProductDetail
      },
      {
        path: 'categories',
        name: 'Categories',
        component: CategoryPage
      },
      {
        path: 'categories/:id',
        name: 'CategoryDetail',
        component: CategoryPage
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
      },
      {
        path: 'orders/:id/receipt',
        name: 'Receipt',
        component: ReceiptPage
      },
      {
        path: 'cart',
        name: 'Cart',
        component: CartPage
      },
      {
        path: 'checkout',
        name: 'Checkout',
        component: CheckoutPage
      },
      {
        path: 'profile',
        name: 'Profile',
        component: UserProfile
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
      next('/admin/dashboard')
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
        next('/admin/dashboard')
      } else {
        next('/home')
      }
      return
    }
  }

  next()
})

export default router