<template>
  <div class="min-h-screen bg-white">
    <Navbar />

    <!-- Hero Section -->
    <section
      class="relative text-white py-32 px-4 bg-gradient-to-br from-blue-500 to-purple-600"
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
            class="inline-block bg-white text-blue-500 font-bold py-4 px-10 rounded-lg hover:bg-gray-100 transition text-lg"
          >
            Start Shopping
          </router-link>

          <button
            class="inline-block border-2 border-white font-bold py-4 px-10 rounded-lg hover:bg-white hover:text-blue-500 transition text-lg"
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
            <div class="text-4xl mb-3"><i class="fas fa-trophy text-yellow-500"></i></div>
            <h3 class="font-bold text-lg mb-1">Award Winning</h3>
            <p class="text-gray-600">Best Online Store 2024</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-3"><i class="fas fa-shield-alt text-green-500"></i></div>
            <h3 class="font-bold text-lg mb-1">Verified Safe</h3>
            <p class="text-gray-600">SSL Encrypted & Secure</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-3"><i class="fas fa-users text-blue-500"></i></div>
            <h3 class="font-bold text-lg mb-1">{{ stats.customers }} Happy Customers</h3>
            <p class="text-gray-600">Trusted Worldwide</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-3"><i class="fas fa-star text-yellow-400"></i></div>
            <h3 class="font-bold text-lg mb-1">{{ stats.rating }} Rating</h3>
            <p class="text-gray-600">Based on {{ stats.reviewsCount }} Reviews</p>
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
          <div class="text-6xl mb-4"><i class="fas fa-headphones text-blue-500"></i></div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Electronics</h3>
          <p class="text-gray-700 mb-4">Latest gadgets & devices</p>
          <button class="text-blue-500 font-bold hover:text-blue-600">Browse →</button>
        </div>

        <div
          class="bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg p-8 text-center hover:shadow-lg transition cursor-pointer"
        >
          <div class="text-6xl mb-4"><i class="fas fa-mouse text-purple-600"></i></div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Accessories</h3>
          <p class="text-gray-700 mb-4">Premium add-ons & extras</p>
          <button class="text-purple-600 font-bold hover:text-purple-800">Browse →</button>
        </div>

        <div
          class="bg-gradient-to-br from-pink-100 to-pink-200 rounded-lg p-8 text-center hover:shadow-lg transition cursor-pointer"
        >
          <div class="text-6xl mb-4"><i class="fas fa-bolt text-pink-500"></i></div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Power Solutions</h3>
          <p class="text-gray-700 mb-4">Chargers & cables</p>
          <button class="text-pink-600 font-bold hover:text-pink-800">Browse →</button>
        </div>

        <div
          class="bg-gradient-to-br from-green-100 to-green-200 rounded-lg p-8 text-center hover:shadow-lg transition cursor-pointer"
        >
          <div class="text-6xl mb-4"><i class="fas fa-bullseye text-green-600"></i></div>
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
            <div class="text-5xl mb-4"><i class="fas fa-truck text-blue-500"></i></div>
            <h3 class="text-2xl font-bold mb-3">Fast Shipping</h3>
            <p class="text-gray-600">Free shipping on orders over $50. Get your products within 3-5 business days.</p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4"><i class="fas fa-dollar-sign text-green-500"></i></div>
            <h3 class="text-2xl font-bold mb-3">Best Prices</h3>
            <p class="text-gray-600">We guarantee the lowest prices on the market. Price match guarantee on all products.</p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4"><i class="fas fa-lock text-purple-500"></i></div>
            <h3 class="text-2xl font-bold mb-3">Secure Payment</h3>
            <p class="text-gray-600">Your payments are 100% secure with SSL encryption and multiple payment options.</p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4"><i class="fas fa-undo-alt text-orange-500"></i></div>
            <h3 class="text-2xl font-bold mb-3">Easy Returns</h3>
            <p class="text-gray-600">30-day money-back guarantee. Return any product hassle-free if you're not satisfied.</p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4"><i class="fas fa-headset text-teal-500"></i></div>
            <h3 class="text-2xl font-bold mb-3">24/7 Support</h3>
            <p class="text-gray-600">Our dedicated support team is available round the clock to help you with any issues.</p>
          </div>

          <div class="bg-white rounded-lg p-8 shadow-md">
            <div class="text-5xl mb-4"><i class="fas fa-gift text-red-400"></i></div>
            <h3 class="text-2xl font-bold mb-3">Special Offers</h3>
            <p class="text-gray-600">Exclusive deals, flash sales, and loyalty rewards for our valued customers.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products Showcase -->
    <section class="max-w-7xl mx-auto px-4 py-16">
      <h2 class="text-4xl font-bold text-center mb-12">Best Sellers</h2>
      <div v-if="loadingProducts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="i in 4" :key="i" class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm">
          <div class="skeleton skeleton-image h-48 !rounded-none"></div>
          <div class="p-4 space-y-3">
            <div class="skeleton skeleton-text-sm"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text-lg w-24"></div>
          </div>
        </div>
      </div>
      <div v-else-if="featuredProducts.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="product in featuredProducts"
          :key="product.id"
          class="product-card"
        >
          <div class="relative">
            <router-link :to="`/products/${product.id}`">
              <img
                :src="product.image_url || 'https://placehold.co/300x200/e2e8f0/64748b?text=No+Image'"
                :alt="product.name"
                class="product-img w-full h-48 object-cover"
              />
            </router-link>
          </div>
          <div class="p-4">
            <div class="flex items-center gap-1 mb-2">
              <template v-if="product.reviews_count">
                <span v-for="s in 5" :key="s" class="text-xs" :class="s <= starFill(product.reviews_avg_rating) ? 'text-yellow-400' : 'text-gray-200'"><i class="fas fa-star"></i></span>
                <span class="text-gray-400 text-xs ml-1">({{ product.reviews_count }})</span>
              </template>
              <span v-else class="text-gray-400 text-xs">No reviews yet</span>
            </div>
            <router-link :to="`/products/${product.id}`" class="font-bold text-lg mb-1 block hover:text-blue-500 transition line-clamp-1">{{ product.name }}</router-link>
            <p v-if="product.category" class="text-gray-500 text-sm mb-3">{{ product.category.name }}</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold text-blue-500">${{ Number(product.price).toFixed(2) }}</span>
              <button @click="addToCart(product)" :disabled="product.stock === 0 || addingId === product.id"
                class="bg-blue-500 text-white px-3 py-1.5 rounded-lg hover:bg-blue-600 transition text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                <i v-if="addingId === product.id" class="fas fa-spinner fa-spin"></i>
                <span v-else>Add</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-12 text-gray-400">
        <p>No products available yet.</p>
      </div>
      <div class="text-center mt-12">
        <router-link
          to="/home"
          class="btn-primary text-base py-3 px-10"
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
              <span class="text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
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
              <span class="text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
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
              <span class="text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
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
            class="btn-primary text-lg py-4 px-10"
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
              <li><i class="fas fa-envelope mr-2"></i> support@onlineshop.com</li>
              <li><i class="fas fa-phone mr-2"></i> +855 12 345 678</li>
              <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Shop Street, Phnom Penh</li>
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
import Navbar from '@/components/Navbar.vue'
import { get, post } from '@/services/api'
import { starFill } from '@/utils/rating'

export default {
  name: 'WelcomePage',
  components: { Navbar },
  data() {
    return {
      stats: { customers: '—', rating: '—', reviewsCount: 0 },
      featuredProducts: [],
      loadingProducts: false,
      addingId: null,
    }
  },
  async mounted() {
    await Promise.all([this.fetchStats(), this.fetchFeaturedProducts()])
  },
  methods: {
    starFill,
    async fetchStats() {
      try {
        const s = await get('/api/stats')
        this.stats = {
          customers: s.customers ?? 0,
          rating: s.reviews_count > 0 ? s.rating + '/5' : 'New',
          reviewsCount: s.reviews_count ?? 0,
        }
      } catch { /* keep placeholders */ }
    },
    async fetchFeaturedProducts() {
      this.loadingProducts = true
      try {
        const data = await get('/api/products', { per_page: 4 })
        this.featuredProducts = data.data || []
      } catch { this.featuredProducts = [] }
      finally { this.loadingProducts = false }
    },
    async addToCart(product) {
      if (this.addingId) return
      if (!localStorage.getItem('token')) { this.$router.push('/login'); return }
      this.addingId = product.id
      try {
        await post('/api/cart', { product_id: product.id, quantity: 1 })
        this.$router.push('/cart')
      } catch { /* ignore */ }
      finally { this.addingId = null }
    },
  },
}
</script>
