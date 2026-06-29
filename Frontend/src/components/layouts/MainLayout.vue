<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Navbar />

    <!-- Page Content -->
    <main class="flex-1">
      <router-view v-slot="{ Component }">
        <transition name="route" mode="out-in">
          <component :is="Component" :key="$route.fullPath" />
        </transition>
      </router-view>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Top: brand + columns -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 py-12">
          <!-- Brand -->
          <div class="md:col-span-4">
            <div class="flex items-center gap-2.5 mb-4">
              <img src="/images/logo.png" alt="Online Shop" class="h-8 w-auto" />
              <span class="text-xl font-semibold tracking-tight text-gray-900">Online Shop</span>
            </div>
            <p class="text-sm text-gray-500 leading-relaxed max-w-xs">
              Your trusted online shopping destination for quality products, delivered to your door.
            </p>
            <div class="flex gap-2 mt-5">
              <a href="#" aria-label="Facebook" class="footer-social">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" aria-label="Twitter" class="footer-social">
                <i class="fab fa-x-twitter"></i>
              </a>
              <a href="#" aria-label="Instagram" class="footer-social">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#" aria-label="YouTube" class="footer-social">
                <i class="fab fa-youtube"></i>
              </a>
            </div>
          </div>

          <!-- Shop links -->
          <div class="md:col-span-2">
            <h4 class="footer-heading">Shop</h4>
            <ul class="space-y-2.5">
              <li><router-link to="/products" class="footer-link">Products</router-link></li>
              <li><router-link to="/categories" class="footer-link">Categories</router-link></li>
              <li><router-link to="/wishlist" class="footer-link">Wishlist</router-link></li>
              <li><router-link to="/cart" class="footer-link">Cart</router-link></li>
              <li><router-link to="/orders" class="footer-link">My Orders</router-link></li>
            </ul>
          </div>

          <!-- Company links -->
          <div class="md:col-span-2">
            <h4 class="footer-heading">Company</h4>
            <ul class="space-y-2.5">
              <li><router-link to="/about" class="footer-link">About</router-link></li>
              <li><router-link to="/contact" class="footer-link">Contact</router-link></li>
              <li><a href="#" class="footer-link">Careers</a></li>
              <li><a href="#" class="footer-link">Blog</a></li>
            </ul>
          </div>

          <!-- Support links -->
          <div class="md:col-span-2">
            <h4 class="footer-heading">Support</h4>
            <ul class="space-y-2.5">
              <li><a href="#" class="footer-link">FAQs</a></li>
              <li><a href="#" class="footer-link">Shipping</a></li>
              <li><a href="#" class="footer-link">Returns</a></li>
              <li><a href="#" class="footer-link">Privacy Policy</a></li>
            </ul>
          </div>

        </div>

        <!-- Bottom bar -->
        <div class="border-t border-gray-100 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
          <p class="text-xs text-gray-500">&copy; 2024 Online Shop. All rights reserved.</p>
          <div class="flex items-center gap-4 text-xs text-gray-500">
            <a href="#" class="hover:text-gray-900 transition">Terms</a>
            <a href="#" class="hover:text-gray-900 transition">Privacy</a>
            <a href="#" class="hover:text-gray-900 transition">Cookies</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import Navbar from '@/components/Navbar.vue'

export default {
  name: 'MainLayout',
  components: { Navbar },
}
</script>

<style scoped>
.footer-heading {
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #6b7280;
  margin-bottom: 0.875rem;
}
.footer-link {
  font-size: 0.875rem;
  color: #6b7280;
  transition: color 0.15s ease;
}
.footer-link:hover {
  color: #111827;
}
.footer-social {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border: 1px solid #e5e7eb;
  border-radius: 9999px;
  color: #6b7280;
  font-size: 0.8rem;
  transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
}
.footer-social:hover {
  background: #111827;
  color: #fff;
  border-color: #111827;
}
</style>
