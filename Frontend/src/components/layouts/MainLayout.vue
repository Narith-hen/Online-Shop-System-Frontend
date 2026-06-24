<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <Navbar />

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
                <router-link to="/home" class="hover:text-white transition">Home</router-link>
              </li>
              <li>
                <router-link to="/products" class="hover:text-white transition"
                  >Products</router-link
                >
              </li>
              <li>
                <router-link to="/about" class="hover:text-white transition">About</router-link>
              </li>
              <li>
                <router-link to="/contact" class="hover:text-white transition">Contact</router-link>
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
              <li>support@onlineshop.com</li>
              <li>+1-800-123-4567</li>
              <li>123 Shop Street, City, Country</li>
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

