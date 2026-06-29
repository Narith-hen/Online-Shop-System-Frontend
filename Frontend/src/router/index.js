import { createRouter, createWebHistory } from 'vue-router'
import { get } from '@/services/api'
import { useAuth } from '@/stores/auth'

import Login from '@/components/Auth/Login.vue'
import Register from '@/components/Auth/Register.vue'
import NotFound from '@/views/NotFound.vue'
import MainLayout from '@/components/layouts/MainLayout.vue'

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
import WishlistPage from '@/views/customer/WishlistPage.vue'
import ReceiptPage from '@/views/customer/ReceiptPage.vue'
import WelcomePage from '@/views/WelcomePage.vue'
import AuthCallback from '@/components/Auth/AuthCallback.vue'
import AccountBlocked from '@/views/AccountBlocked.vue'

const routes = [
  { path: '/', name: 'Welcome', component: WelcomePage },
  { path: '/login', name: 'Login', component: Login, meta: { guest: true } },
  { path: '/register', name: 'Register', component: Register, meta: { guest: true } },
  { path: '/auth/callback', name: 'AuthCallback', component: AuthCallback, meta: { guest: true } },
  { path: '/account-blocked', name: 'AccountBlocked', component: AccountBlocked, meta: { guest: true } },

  {
    path: '/',
    component: MainLayout,
    meta: { requiresAuth: true, role: 'customer' },
    children: [
      { path: 'home', name: 'CustomerHome', component: CustomerHome },
      { path: 'products', name: 'Products', component: CustomerProduct },
      { path: 'products/:id', name: 'ProductDetail', component: ProductDetail },
      { path: 'categories', name: 'Categories', component: CategoryPage },
      { path: 'categories/:id', name: 'CategoryDetail', component: CategoryPage },
      { path: 'about', name: 'About', component: CustomerAbout },
      { path: 'contact', name: 'Contact', component: CustomerContact },
      { path: 'orders', name: 'Orders', component: CustomerOrderCard },
      { path: 'orders/:id/receipt', name: 'Receipt', component: ReceiptPage },
      { path: 'cart', name: 'Cart', component: CartPage },
      { path: 'checkout', name: 'Checkout', component: CheckoutPage },
      { path: 'wishlist', name: 'Wishlist', component: WishlistPage },
      { path: 'profile', name: 'Profile', component: UserProfile },
    ],
  },

  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  if (to.path === '/login' && to.query.logout) {
    const { clearAuth } = useAuth()
    clearAuth()
    next()
    return
  }

  const auth = useAuth()
  auth.refresh()
  const token = auth.token
  const userRole = auth.user?.role

  if (to.meta.guest && token) {
    next('/home')
    return
  }

  const matchedRoute = to.matched.find(r => r.meta.requiresAuth)
  if (matchedRoute) {
    if (!token) {
      next('/login')
      return
    }

    try {
      const res = await get('/api/profile')
      if (!res) throw new Error('Invalid response')
    } catch (err) {
      if (err.status === 403) {
        const { clearAuth } = useAuth()
        clearAuth()
        next('/account-blocked')
        return
      }
      const { clearAuth } = useAuth()
      clearAuth()
      next('/login')
      return
    }

    const requiredRole = matchedRoute.meta.role
    if (requiredRole && userRole !== requiredRole) {
      next('/home')
      return
    }
  }

  next()
})

export default router
