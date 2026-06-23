<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <div class="flex items-center gap-3">
            <img src="/images/logo.png" alt="Online Shop" class="h-10 w-auto" />
            <span class="text-2xl font-bold text-gray-900">Online Shop</span>
          </div>

          <!-- Navigation Links -->
          <div class="hidden md:flex gap-8">
            <router-link to="/home" class="text-gray-700 hover:text-blue-600 font-medium transition">
              Home
            </router-link>
            <router-link
              to="/products"
              class="text-gray-700 hover:text-blue-600 font-medium transition"
            >
              Products
            </router-link>
            <router-link
              to="/about"
              class="text-gray-700 hover:text-blue-600 font-medium transition"
            >
              About
            </router-link>
            <router-link
              to="/contact"
              class="text-gray-700 hover:text-blue-600 font-medium transition"
            >
              Contact
            </router-link>
          </div>

          <!-- User Menu -->
          <div class="flex items-center gap-4">
            <!-- Cart Icon -->
            <button class="relative text-gray-700 hover:text-blue-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                ></path>
              </svg>
              <span
                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
                >3</span
              >
            </button>

            <!-- Profile Dropdown -->
            <div class="relative group">
              <button class="text-gray-700 hover:text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  ></path>
                </svg>
              </button>
              <div
                class="absolute right-0 w-48 bg-white rounded-lg shadow-xl hidden group-hover:block"
              >
                <router-link to="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                  >My Profile</router-link
                >
                <router-link to="/customer/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                  >My Orders</router-link
                >
                <router-link to="/settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                  >Settings</router-link
                >
                <hr class="my-2" />
                <button
                  @click="handleLogout"
                  class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
                >
                  <span>Logout</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-1">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          <!-- Company Info -->
          <div>
            <h3 class="text-xl font-bold mb-4">Online Shop</h3>
            <p class="text-gray-400">
              Your trusted online shopping destination for quality products.
            </p>
          </div>

          <!-- Quick Links -->
          <div>
            <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
            <ul class="space-y-2 text-gray-400">
              <li>
                <router-link to="/customer/home" class="hover:text-white transition">Home</router-link>
              </li>
              <li>
                <router-link to="/customer/products" class="hover:text-white transition"
                  >Products</router-link
                >
              </li>
              <li>
                <router-link to="/customer/about" class="hover:text-white transition">About</router-link>
              </li>
              <li>
                <router-link to="/customer/contact" class="hover:text-white transition">Contact</router-link>
              </li>
            </ul>
          </div>

          <!-- Customer Service -->
          <div>
            <h4 class="text-lg font-semibold mb-4">Support</h4>
            <ul class="space-y-2 text-gray-400">
              <li><a href="#" class="hover:text-white transition">FAQs</a></li>
              <li><a href="#" class="hover:text-white transition">Shipping Info</a></li>
              <li><a href="#" class="hover:text-white transition">Returns</a></li>
              <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
            </ul>
          </div>

          <!-- Contact Info -->
          <div>
            <h4 class="text-lg font-semibold mb-4">Contact</h4>
            <ul class="space-y-2 text-gray-400">
              <li>📧 support@onlineshop.com</li>
              <li>📞 +1-800-123-4567</li>
              <li>📍 123 Shop Street, City, Country</li>
              <li class="flex gap-3 mt-4">
                <a href="#" class="text-blue-400 hover:text-blue-300">Facebook</a>
                <a href="#" class="text-blue-400 hover:text-blue-300">Twitter</a>
                <a href="#" class="text-blue-400 hover:text-blue-300">Instagram</a>
              </li>
            </ul>
          </div>
        </div>

        <hr class="border-gray-700 mb-6" />
        <div class="text-center text-gray-400">
          <p>&copy; 2024 Online Shop. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogout = () => {
  localStorage.removeItem('token')
  router.push('/login')
}
</script>