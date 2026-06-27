<template>
  <div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 overflow-hidden">
      <!-- Decorative blobs -->
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-32 -left-20 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl"></div>

      <div class="relative max-w-7xl mx-auto px-4 py-20 lg:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <!-- Copy -->
          <div class="text-white text-center lg:text-left animate-fade-in-up">
            <span
              class="inline-block bg-white/15 backdrop-blur-sm text-white text-sm font-semibold px-4 py-1.5 rounded-full mb-5 ring-1 ring-white/20"
            >
              New arrivals every week
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-5 leading-tight">
              Welcome to <span class="text-yellow-300">Online Shop</span>
            </h1>
            <p class="text-lg sm:text-xl text-blue-100 mb-8 max-w-xl mx-auto lg:mx-0">
              Discover amazing products at unbeatable prices, handpicked for you and delivered to your door.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
              <router-link
                to="/products"
                class="inline-flex items-center justify-center bg-white text-blue-600 font-bold py-3 px-8 rounded-lg hover:bg-blue-50 hover:scale-105 transition shadow-lg"
              >
                <i class="fas fa-shopping-bag mr-2"></i>
                Shop Now
              </router-link>
              <router-link
                to="/about"
                class="inline-flex items-center justify-center bg-transparent text-white font-bold py-3 px-8 rounded-lg border-2 border-white/40 hover:bg-white/10 transition"
              >
                Learn More
              </router-link>
            </div>

            <!-- Mini stats -->
            <div class="mt-10 grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0">
              <div class="text-center lg:text-left">
                <p class="text-2xl font-bold">10k+</p>
                <p class="text-sm text-blue-200">Products</p>
              </div>
              <div class="text-center lg:text-left">
                <p class="text-2xl font-bold">5k+</p>
                <p class="text-sm text-blue-200">Customers</p>
              </div>
              <div class="text-center lg:text-left">
                <p class="text-2xl font-bold">4.9&#9733;</p>
                <p class="text-sm text-blue-200">Rating</p>
              </div>
            </div>
          </div>

          <!-- Image -->
          <div class="relative hidden lg:block animate-fade-in-right">
            <div class="absolute inset-0 bg-white/10 rounded-3xl rotate-6"></div>
            <img
              :src="welcomeImg"
              alt="Welcome to Online Shop"
              class="relative rounded-3xl shadow-2xl ring-1 ring-white/20 w-full h-[460px] object-cover"
            />
            <!-- Floating badge -->
            <div
              class="absolute -bottom-5 -left-5 bg-white rounded-xl shadow-xl px-5 py-3 flex items-center gap-3 animate-float"
            >
              <span class="flex items-center justify-center w-10 h-10 bg-green-100 text-green-600 rounded-full">
                <i class="fas fa-truck"></i>
              </span>
              <div>
                <p class="text-sm font-bold text-gray-900">Free Shipping</p>
                <p class="text-xs text-gray-500">On orders over $50</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center p-6 bg-white rounded-lg shadow-md animate-fade-in-up" style="animation-delay: 0ms">
          <div class="text-4xl mb-3">🚚</div>
          <h3 class="text-xl font-bold mb-2">Free Shipping</h3>
          <p class="text-gray-600">On orders over $50</p>
        </div>
        <div class="text-center p-6 bg-white rounded-lg shadow-md animate-fade-in-up" style="animation-delay: 120ms">
          <div class="text-4xl mb-3">🔒</div>
          <h3 class="text-xl font-bold mb-2">Secure Payment</h3>
          <p class="text-gray-600">100% safe transactions</p>
        </div>
        <div class="text-center p-6 bg-white rounded-lg shadow-md animate-fade-in-up" style="animation-delay: 240ms">
          <div class="text-4xl mb-3">♻️</div>
          <h3 class="text-xl font-bold mb-2">Easy Returns</h3>
          <p class="text-gray-600">30-day return policy</p>
        </div>
      </div>
    </section>

    <!-- Featured Products Section -->
    <section v-if="featuredProducts.length" class="max-w-7xl mx-auto px-4 py-12">
      <h2 class="text-4xl font-bold mb-8 text-center animate-fade-in-down">Featured Products</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="(product, index) in featuredProducts"
          :key="product.id"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg hover:-translate-y-1 transition animate-fade-in-up"
          :style="{ animationDelay: `${index * 80}ms` }"
        >
          <router-link :to="`/products/${product.id}`">
            <img
              :src="product.image_url || 'https://via.placeholder.com/300x200'"
              :alt="product.name"
              class="w-full h-48 object-cover"
            />
          </router-link>
          <div class="p-4">
            <p v-if="product.category" class="text-xs text-blue-600 font-semibold mb-1">
              {{ product.category.name }}
            </p>
            <router-link
              :to="`/products/${product.id}`"
              class="font-bold text-lg mb-2 block hover:text-blue-600 transition"
            >
              {{ product.name }}
            </router-link>
            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
              {{ product.description || 'No description available.' }}
            </p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold text-blue-600">${{ product.price?.toFixed(2) }}</span>
              <button
                @click.stop="addToCart(product)"
                :disabled="product.stock === 0 || addingId === product.id"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <i v-if="addingId === product.id" class="fas fa-spinner fa-spin mr-1"></i>
                {{ addingId === product.id ? 'Adding...' : 'Add to Cart' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-12">
        <router-link
          to="/products"
          class="inline-block bg-gray-900 text-white font-bold py-3 px-8 rounded-lg hover:bg-gray-800 transition"
        >
          View All Products
        </router-link>
      </div>
    </section>

    <!-- Loading for featured -->
    <div v-else-if="loadingProducts" class="text-center py-8">
      <i class="fas fa-spinner fa-spin text-3xl text-blue-600"></i>
    </div>

    <SuccessModal ref="successModal" />
  </div>
</template>

<script>
import welcomeImg from '@/assets/images/welcome.png'
import SuccessModal from '@/components/SuccessModal.vue'

export default {
  components: { SuccessModal },
  name: 'CustomerHome',
  data() {
    return {
      welcomeImg,
      featuredProducts: [],
      loadingProducts: false,
      addingId: null,
    }
  },
  async mounted() {
    await this.fetchFeaturedProducts()
  },
  methods: {
    async fetchFeaturedProducts() {
      this.loadingProducts = true
      try {
        const token = localStorage.getItem('token')
        const headers = {}
        if (token) headers.Authorization = `Bearer ${token}`
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/products?per_page=8&_=${Date.now()}`, { headers })
        if (res.ok) {
          const data = await res.json()
          this.featuredProducts = data.data || data || []
        }
      } catch {
        this.featuredProducts = []
      } finally {
        this.loadingProducts = false
      }
    },
    async addToCart(product) {
      if (this.addingId) return
      this.addingId = product.id
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/cart`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          body: JSON.stringify({
            product_id: product.id,
            quantity: 1,
          }),
        })
        if (res.ok) {
          this.$refs.successModal.show('You ordered product "' + product.name + '" successfully.')
        } else {
          const data = await res.json()
          alert(data.message || 'Failed to add to cart.')
        }
      } catch {
        alert('Failed to add to cart.')
      } finally {
        this.addingId = null
      }
    },
  },
}
</script>
