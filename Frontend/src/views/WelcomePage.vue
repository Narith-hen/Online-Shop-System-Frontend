<template>
  <div class="min-h-screen bg-white">
    <Navbar />

    <!-- Hero Section -->
    <section
      class="relative text-white py-32 px-4"
      :style="{
        backgroundImage: `url(${welcomeImg})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        backgroundRepeat: 'no-repeat',
        minHeight: '50vh',
      }"
    >
      <!-- Dark overlay -->
      <div class="absolute inset-0 bg-black/40"></div>

      <div class="relative max-w-7xl mx-auto text-center">
        <h1 class="text-6xl md:text-7xl font-black mb-6 leading-tight">Welcome to Online Shop</h1>

        <p class="text-xl md:text-2xl mb-8 text-gray-100">
          Discover Premium Products at Unbeatable Prices
        </p>

        <div class="flex flex-col md:flex-row gap-4 justify-center">
          <router-link
            to="/home"
            class="inline-block bg-white text-blue-600 font-bold py-4 px-10 rounded-lg hover:bg-gray-100 transition text-lg"
          >
            Start Shopping
          </router-link>

          <button
            class="inline-block border-2 border-white font-bold py-4 px-10 rounded-lg hover:bg-white hover:text-blue-600 transition text-lg"
          >
            Watch Demo
          </button>
        </div>
      </div>
    </section>

    <!-- Trust Badges -->
    <section class="bg-gray-50 py-12 px-4">
      <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="text-4xl mb-3">🏆</div>
            <h3 class="font-bold text-lg mb-1">Award Winning</h3>
            <p class="text-gray-600">Best Online Store 2024</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-3">✅</div>
            <h3 class="font-bold text-lg mb-1">Verified Safe</h3>
            <p class="text-gray-600">SSL Encrypted & Secure</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-3">👥</div>
            <h3 class="font-bold text-lg mb-1">50K+ Happy Customers</h3>
            <p class="text-gray-600">Trusted Worldwide</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-3">⭐</div>
            <h3 class="font-bold text-lg mb-1">4.9/5 Rating</h3>
            <p class="text-gray-600">Based on 10K+ Reviews</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Categories -->
    <section class="max-w-7xl mx-auto px-4 py-16">
      <h2 class="text-4xl font-bold text-center mb-12">Shop by Category</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg p-8 text-center hover:shadow-lg transition cursor-pointer"
        >
          <div class="text-6xl mb-4">🎧</div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Electronics</h3>
          <p class="text-gray-700 mb-4">Latest gadgets & devices</p>
          <button class="text-blue-600 font-bold hover:text-blue-800">Browse →</button>
        </div>

        <div
          class="bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg p-8 text-center hover:shadow-lg transition cursor-pointer"
        >
          <div class="text-6xl mb-4">🖱️</div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Accessories</h3>
          <p class="text-gray-700 mb-4">Premium add-ons & extras</p>
          <button class="text-purple-600 font-bold hover:text-purple-800">Browse →</button>
        </div>

        <div
          class="bg-gradient-to-br from-pink-100 to-pink-200 rounded-lg p-8 text-center hover:shadow-lg transition cursor-pointer"
        >
          <div class="text-6xl mb-4">⚡</div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Power Solutions</h3>
          <p class="text-gray-700 mb-4">Chargers & cables</p>
          <button class="text-pink-600 font-bold hover:text-pink-800">Browse →</button>
        </div>

        <div
          class="bg-gradient-to-br from-green-100 to-green-200 rounded-lg p-8 text-center hover:shadow-lg transition cursor-pointer"
        >
          <div class="text-6xl mb-4">🎯</div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Smart Gadgets</h3>
          <p class="text-gray-700 mb-4">IoT & smart devices</p>
          <button class="text-green-600 font-bold hover:text-green-800">Browse →</button>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-gray-50 py-16 px-4">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-4xl font-bold text-center mb-12">Why Shop With Us?</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4">🚚</div>
            <h3 class="text-2xl font-bold mb-3">Fast Shipping</h3>
            <p class="text-gray-600">
              Free shipping on orders over $50. Get your products within 3-5 business days.
            </p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4">💰</div>
            <h3 class="text-2xl font-bold mb-3">Best Prices</h3>
            <p class="text-gray-600">
              We guarantee the lowest prices on the market. Price match guarantee on all products.
            </p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4">🔒</div>
            <h3 class="text-2xl font-bold mb-3">Secure Payment</h3>
            <p class="text-gray-600">
              Your payments are 100% secure with SSL encryption and multiple payment options.
            </p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4">🔄</div>
            <h3 class="text-2xl font-bold mb-3">Easy Returns</h3>
            <p class="text-gray-600">
              30-day money-back guarantee. Return any product hassle-free if you're not satisfied.
            </p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4">💬</div>
            <h3 class="text-2xl font-bold mb-3">24/7 Support</h3>
            <p class="text-gray-600">
              Our dedicated support team is available round the clock to help you with any issues.
            </p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4">🎁</div>
            <h3 class="text-2xl font-bold mb-3">Special Offers</h3>
            <p class="text-gray-600">
              Exclusive deals, flash sales, and loyalty rewards for our valued customers.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products Showcase -->
    <section class="max-w-7xl mx-auto px-4 py-16">
      <h2 class="text-4xl font-bold text-center mb-12">Best Sellers</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="product in 4"
          :key="product"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition"
        >
          <div class="relative">
            <img
              src="https://via.placeholder.com/300x200?text=Product"
              alt="Product"
              class="w-full h-48 object-cover"
            />
            <div
              class="absolute top-3 right-3 bg-red-500 text-white px-4 py-1 rounded-full text-sm font-bold"
            >
              -30%
            </div>
          </div>
          <div class="p-4">
            <div class="flex items-center gap-1 mb-2">
              <span class="text-yellow-400">⭐⭐⭐⭐⭐</span>
              <span class="text-gray-600 text-sm">(256)</span>
            </div>
            <h3 class="font-bold text-lg mb-2">Premium Product</h3>
            <p class="text-gray-600 text-sm mb-3">High quality with amazing features</p>
            <div class="flex justify-between items-center">
              <div>
                <span class="text-2xl font-bold text-blue-600">$49.99</span>
                <span class="text-sm text-gray-400 line-through ml-2">$69.99</span>
              </div>
              <button
                class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition text-sm"
              >
                Add
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-12">
        <router-link
          to="/home"
          class="inline-block bg-gray-900 text-white font-bold py-3 px-10 rounded-lg hover:bg-gray-800 transition"
        >
          View All Products
        </router-link>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="bg-gray-50 py-16 px-4">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-4xl font-bold text-center mb-12">What Our Customers Say</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="flex items-center gap-1 mb-4">
              <span class="text-yellow-400">⭐⭐⭐⭐⭐</span>
            </div>
            <p class="text-gray-600 mb-4">
              "Amazing selection and great prices! The checkout process was smooth and my order
              arrived in perfect condition. Will definitely shop here again!"
            </p>
            <div class="flex items-center gap-3">
              <img
                src="https://via.placeholder.com/50x50?text=User"
                alt="User"
                class="w-12 h-12 rounded-full object-cover"
              />
              <div>
                <p class="font-bold text-gray-900">Sarah Johnson</p>
                <p class="text-sm text-gray-600">Verified Buyer</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="flex items-center gap-1 mb-4">
              <span class="text-yellow-400">⭐⭐⭐⭐⭐</span>
            </div>
            <p class="text-gray-600 mb-4">
              "Fast shipping and excellent customer service. The product quality is outstanding.
              I've recommended this shop to all my friends!"
            </p>
            <div class="flex items-center gap-3">
              <img
                src="https://via.placeholder.com/50x50?text=User"
                alt="User"
                class="w-12 h-12 rounded-full object-cover"
              />
              <div>
                <p class="font-bold text-gray-900">Mike Chen</p>
                <p class="text-sm text-gray-600">Verified Buyer</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="flex items-center gap-1 mb-4">
              <span class="text-yellow-400">⭐⭐⭐⭐⭐</span>
            </div>
            <p class="text-gray-600 mb-4">
              "Best online shopping experience I've had! Great product variety, competitive prices,
              and the return policy is hassle-free."
            </p>
            <div class="flex items-center gap-3">
              <img
                src="https://via.placeholder.com/50x50?text=User"
                alt="User"
                class="w-12 h-12 rounded-full object-cover"
              />
              <div>
                <p class="font-bold text-gray-900">Emma Davis</p>
                <p class="text-sm text-gray-600">Verified Buyer</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    

    <!-- CTA Section -->
    <section class="bg-gray-900 text-white py-16 px-4">
      <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-4xl font-bold mb-6">Ready to Start Shopping?</h2>
        <p class="text-xl text-gray-300 mb-8">Join thousands of satisfied customers today!</p>
        <div class="flex flex-col md:flex-row gap-4 justify-center">
          <router-link
            to="/home"
            class="inline-block bg-blue-600 text-white font-bold py-4 px-10 rounded-lg hover:bg-blue-700 transition text-lg"
          >
            Enter the Shop
          </router-link>
          <button
            class="inline-block border-2 border-white text-white font-bold py-4 px-10 rounded-lg hover:bg-white hover:text-gray-900 transition text-lg"
          >
            Contact Sales
          </button>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12 px-4">
      <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          <div>
            <h3 class="text-xl font-bold text-white mb-4">Online Shop</h3>
            <p class="text-sm">Your trusted partner in online shopping since 2020.</p>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-white mb-4">Quick Links</h4>
            <ul class="space-y-2 text-sm">
              <li><a href="#" class="hover:text-white transition">Home</a></li>
              <li><a href="#" class="hover:text-white transition">Products</a></li>
              <li><a href="#" class="hover:text-white transition">About Us</a></li>
              <li><a href="#" class="hover:text-white transition">Contact</a></li>
            </ul>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-white mb-4">Support</h4>
            <ul class="space-y-2 text-sm">
              <li><a href="#" class="hover:text-white transition">FAQs</a></li>
              <li><a href="#" class="hover:text-white transition">Shipping Info</a></li>
              <li><a href="#" class="hover:text-white transition">Returns</a></li>
              <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
            </ul>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-white mb-4">Contact</h4>
            <ul class="space-y-2 text-sm">
              <li>📧 support@onlineshop.com</li>
              <li>📞 +1-800-123-4567</li>
              <li>📍 123 Shop Street, City</li>
            </ul>
          </div>
        </div>

        <hr class="border-gray-700 mb-6" />

        <div class="flex flex-col md:flex-row justify-between items-center text-sm">
          <p>&copy; 2024 Online Shop. All rights reserved.</p>
          <div class="flex gap-6 mt-4 md:mt-0">
            <a href="#" class="text-blue-400 hover:text-blue-300">Facebook</a>
            <a href="#" class="text-blue-400 hover:text-blue-300">Twitter</a>
            <a href="#" class="text-blue-400 hover:text-blue-300">Instagram</a>
            <a href="#" class="text-blue-400 hover:text-blue-300">LinkedIn</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import welcomeImg from '@/assets/images/welcome.png'
import Navbar from '@/components/Navbar.vue'

export default {
  name: 'WelcomePage',
  components: { Navbar },
  data() {
    return {
      welcomeImg,
    }
  },
}
</script>
