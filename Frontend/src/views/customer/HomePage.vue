<template>
  <div>
    <section class="relative text-white overflow-hidden" style="background-image: url('https://intelcorp.scene7.com/is/image/intelcorp/laptop-marquee-16x9:1920-1080?wid=1072&hei=603&fmt=webp-alpha'); background-size: cover; background-position: center; font-family: Tahoma, Helvetica, Arial, sans-serif;">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-[3px]"></div>

      <div class="relative max-w-7xl mx-auto px-4 py-20 lg:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div class="text-white text-center lg:text-left animate-fade-in-up">
            <span class="inline-flex items-center gap-1.5 bg-white/15 backdrop-blur-sm text-white text-sm font-semibold px-4 py-1.5 rounded-full mb-5 ring-1 ring-white/20">
              <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
              New arrivals every week
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-5 leading-tight">
              Welcome to <span class="text-yellow-300">Online Shop</span>
            </h1>
            <p class="text-lg sm:text-xl text-blue-200 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
              Discover amazing products at unbeatable prices, handpicked for you and delivered to your door.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
              <router-link to="/products" class="inline-flex items-center justify-center bg-white text-blue-500 font-bold py-3.5 px-9 rounded-xl hover:bg-blue-50 hover:scale-105 active:scale-100 transition-all shadow-lg shadow-black/10">
                <i class="fas fa-bag-shopping mr-2"></i> Shop Now
              </router-link>
              <router-link to="/about" class="inline-flex items-center justify-center bg-transparent text-white font-semibold py-3.5 px-9 rounded-xl border-2 border-white/30 hover:bg-white/10 hover:border-white/50 transition-all">
                <i class="fas fa-circle-info mr-2"></i> Learn More
              </router-link>
            </div>
            <div class="mt-12 grid grid-cols-3 gap-6 max-w-md mx-auto lg:mx-0">
              <div class="text-center lg:text-left">
                <p class="text-3xl font-black text-white">{{ stats.products }}</p>
                <p class="text-sm text-blue-200 mt-1">Products</p>
              </div>
              <div class="text-center lg:text-left">
                <p class="text-3xl font-black text-white">{{ stats.customers }}</p>
                <p class="text-sm text-blue-200 mt-1">Customers</p>
              </div>
              <div class="text-center lg:text-left">
                <p class="text-3xl font-black text-yellow-300">{{ stats.rating }}</p>
                <p class="text-sm text-blue-200 mt-1">Rating</p>
              </div>
            </div>
          </div>

          <div class="relative hidden lg:flex items-center justify-center animate-fade-in-right">
            <div class="w-full max-w-md aspect-square rounded-3xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center p-12">
              <div class="text-center">
                <div class="text-8xl mb-6">🛍️</div>
                <p class="text-xl text-white/80 font-light">Shop the best deals</p>
                <div class="mt-6 flex items-center justify-center gap-2 text-yellow-300 text-2xl">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
              </div>
            </div>
            <div class="absolute -bottom-3 -left-3 bg-white rounded-xl shadow-xl px-5 py-3 flex items-center gap-3 animate-float">
              <span class="flex items-center justify-center w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full">
                <i class="fas fa-truck"></i>
              </span>
              <div>
                <p class="text-sm font-bold text-gray-900">Free Shipping</p>
                <p class="text-xs text-gray-500">On orders over $50</p>
              </div>
            </div>
            <div class="absolute -top-4 -right-4 bg-white rounded-xl shadow-xl px-4 py-2.5 flex items-center gap-2 animate-float" style="animation-delay: 1s">
              <span class="flex items-center justify-center w-8 h-8 bg-amber-100 text-amber-600 rounded-full">
                <i class="fas fa-star"></i>
              </span>
              <div>
                <p class="text-sm font-bold text-gray-900">{{ stats.rating || '—' }} Rating</p>
                <p class="text-xs text-gray-500">From {{ stats.reviewsCount }} reviews</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-16">
      <div class="text-center mb-10 animate-fade-in-down">
        <span class="text-sm font-semibold text-blue-500 uppercase tracking-wider">Why Shop With Us</span>
        <h2 class="text-3xl font-bold text-gray-900 mt-2">Everything You Need</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="(f, i) in features" :key="i"
          class="group bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
          :style="{ animationDelay: `${i * 100}ms` }">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl group-hover:scale-110 transition-transform"
            :class="f.bg">
            <i :class="f.icon"></i>
          </div>
          <h3 class="text-lg font-bold text-gray-900 mb-1.5">{{ f.title }}</h3>
          <p class="text-gray-500 text-sm leading-relaxed">{{ f.desc }}</p>
        </div>
      </div>
    </section>

    <section class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-10 animate-fade-in-down">
          <span class="text-sm font-semibold text-blue-500 uppercase tracking-wider">Shop By</span>
          <h2 class="text-3xl font-bold text-gray-900 mt-2">Popular Categories</h2>
        </div>
        <div v-if="categoriesLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="i in 4" :key="i" class="bg-white rounded-2xl p-8 shadow-sm">
            <div class="skeleton skeleton-image h-24 rounded-xl mb-4"></div>
            <div class="skeleton skeleton-text w-1/2 mx-auto"></div>
            <div class="skeleton skeleton-text-sm w-1/3 mx-auto mt-2"></div>
          </div>
        </div>
        <div v-else-if="categories.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="(cat, i) in categories" :key="cat.id"
            @click="$router.push(`/categories/${cat.id}`)"
            class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center animate-fade-in-up"
            :style="{ animationDelay: `${i * 80}ms` }">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl group-hover:scale-110 transition-transform"
              :class="categoryColors[i % categoryColors.length]">
              <i :class="categoryIcons[i % categoryIcons.length]"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-500 transition-colors">{{ cat.name }}</h3>
            <p class="text-sm text-gray-400 mt-1">{{ cat.products_count || 0 }} products</p>
          </div>
        </div>
        <div v-else class="text-center py-8 text-gray-400">
          <i class="fas fa-tag text-3xl mb-3 block"></i>
          <p>No categories available yet.</p>
        </div>
      </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-16">
      <div class="flex items-center justify-between mb-10 animate-fade-in-down">
        <div>
          <span class="text-sm font-semibold text-blue-500 uppercase tracking-wider">Featured</span>
          <h2 class="text-3xl font-bold text-gray-900 mt-2">Popular Products</h2>
        </div>
        <router-link to="/products" class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-blue-500 hover:text-blue-600 transition">
          View All <i class="fas fa-arrow-right"></i>
        </router-link>
      </div>

      <div v-if="loadingProducts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="i in 4" :key="i" class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm">
          <div class="skeleton skeleton-image h-48 !rounded-none"></div>
          <div class="p-4 space-y-3">
            <div class="skeleton skeleton-text-sm"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text-sm w-3/4"></div>
            <div class="flex justify-between items-center pt-2">
              <div class="skeleton skeleton-text-lg w-24"></div>
              <div class="skeleton skeleton-button"></div>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="featuredProducts.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="(product, index) in featuredProducts" :key="product.id"
          class="product-card animate-fade-in-up" :style="{ animationDelay: `${index * 80}ms` }">
          <div class="relative">
            <router-link :to="`/products/${product.id}`" class="block overflow-hidden">
              <img :src="product.image_url || 'https://placehold.co/300x200/e2e8f0/64748b?text=No+Image'" :alt="product.name"
                class="product-img w-full h-48 object-cover" />
            </router-link>
            <div v-if="product.stock <= 5 && product.stock > 0"
              class="absolute top-3 left-3 bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
              Low stock
            </div>
            <div v-if="product.stock === 0"
              class="absolute inset-0 bg-black/40 flex items-center justify-center rounded-t-2xl">
              <span class="bg-red-500 text-white text-sm font-bold px-4 py-1.5 rounded-full">Out of Stock</span>
            </div>
          </div>
          <div class="p-4">
            <p v-if="product.category" class="text-[11px] text-blue-500 font-semibold mb-1 uppercase tracking-wider">{{ product.category.name }}</p>
            <router-link :to="`/products/${product.id}`" class="font-bold text-sm block hover:text-blue-500 transition leading-snug">
              {{ product.name }}
            </router-link>
            <div class="flex items-center gap-1 mt-2">
              <template v-if="product.reviews_count">
                <span v-for="s in 5" :key="s" class="text-[10px]" :class="s <= starFill(product.reviews_avg_rating) ? 'text-yellow-400' : 'text-gray-200'"><i class="fas fa-star"></i></span>
                <span class="text-[10px] text-gray-400 ml-1">({{ product.reviews_count }})</span>
              </template>
              <span v-else class="text-[10px] text-gray-400">No reviews yet</span>
            </div>
            <div class="flex justify-between items-center mt-3">
              <span class="text-lg font-bold text-blue-500">${{ Number(product.price).toFixed(2) }}</span>
              <button @click.stop="addToCart(product)" :disabled="product.stock === 0 || addingId === product.id"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 active:scale-95 transition-all text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5">
                <i v-if="addingId === product.id" class="fas fa-spinner fa-spin"></i>
                <i v-else class="fas fa-cart-plus text-xs"></i>
                {{ addingId === product.id ? '' : 'Add' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-16 animate-fade-in">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
          <i class="fas fa-box-open text-3xl text-gray-400"></i>
        </div>
        <p class="text-gray-500 font-medium">No featured products available.</p>
      </div>

      <div class="text-center mt-10 sm:hidden">
        <router-link to="/products" class="btn-primary">
          View All Products <i class="fas fa-arrow-right ml-1"></i>
        </router-link>
      </div>
    </section>

    <section class="bg-white py-16 border-t border-gray-100">
      <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12 animate-fade-in-down">
          <span class="text-sm font-semibold text-blue-500 uppercase tracking-wider">Testimonials</span>
          <h2 class="text-3xl font-bold text-gray-900 mt-2">What Our Customers Say</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="(t, i) in testimonials" :key="i"
            class="bg-gray-50 rounded-2xl p-6 border border-gray-100 animate-fade-in-up hover:shadow-md transition-shadow"
            :style="{ animationDelay: `${i * 100}ms` }">
            <div class="flex items-center gap-1 mb-3">
              <span v-for="s in 5" :key="s" class="text-yellow-400 text-xs"><i class="fas fa-star"></i></span>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed mb-4">"{{ t.text }}"</p>
            <div class="flex items-center gap-3">
              <img :src="t.image" :alt="t.name" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200" />
              <div>
                <p class="text-sm font-semibold text-gray-900">{{ t.name }}</p>
                <p class="text-xs text-gray-400">{{ t.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-gradient-to-r from-blue-500 to-indigo-600 py-16">
      <div class="max-w-7xl mx-auto px-4 text-center animate-fade-in-up">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Start Shopping?</h2>
        <p class="text-lg text-blue-100 mb-8 max-w-lg mx-auto">Join thousands of happy customers and discover products you'll love.</p>
        <router-link to="/products" class="inline-flex items-center gap-2 bg-white text-blue-500 font-bold py-3.5 px-10 rounded-xl hover:bg-blue-50 hover:scale-105 active:scale-100 transition-all shadow-lg">
          <i class="fas fa-bag-shopping"></i> Browse Products
        </router-link>
      </div>
    </section>

    <SuccessModal ref="successModal" />
    <Toast ref="toastRef" />
  </div>
</template>

<script>
import SuccessModal from '@/components/SuccessModal.vue'
import Toast from '@/components/Toast.vue'
import { get, post } from '@/services/api'
import { starFill } from '@/utils/rating'

export default {
  components: { SuccessModal, Toast },
  name: 'CustomerHome',
  data() {
    return {
      featuredProducts: [],
      loadingProducts: false,
      categories: [],
      categoriesLoading: false,
      addingId: null,
      stats: { products: '\u2014', customers: '\u2014', rating: '\u2014', reviewsCount: 0 },
      features: [
        { icon: 'fas fa-truck text-emerald-600', bg: 'bg-emerald-100', title: 'Free Shipping', desc: 'Free shipping on all orders over $50. Fast and reliable delivery to your doorstep.' },
        { icon: 'fas fa-shield-halved text-blue-600', bg: 'bg-blue-100', title: 'Secure Payment', desc: '100% safe and encrypted transactions. Your payment information is always protected.' },
        { icon: 'fas fa-arrow-rotate-left text-amber-600', bg: 'bg-amber-100', title: 'Easy Returns', desc: 'Not satisfied? Return any item within 30 days for a full refund, no questions asked.' },
        { icon: 'fas fa-headset text-purple-600', bg: 'bg-purple-100', title: '24/7 Support', desc: 'Our dedicated support team is available around the clock to help with any questions.' },
      ],
      categoryColors: ['bg-blue-100', 'bg-emerald-100', 'bg-amber-100', 'bg-purple-100', 'bg-rose-100', 'bg-cyan-100'],
      categoryIcons: ['fas fa-laptop', 'fas fa-tshirt', 'fas fa-couch', 'fas fa-book', 'fas fa-gem', 'fas fa-futbol'],
      testimonials: [
        { text: 'Absolutely love the quality and fast shipping. I received my order within 2 days and the product exceeded my expectations.', name: 'Sarah J.', role: 'Verified Buyer', image: 'https://i.pinimg.com/736x/36/3e/48/363e4817c5358a3423f961b388941625.jpg' },
        { text: 'Best online shopping experience I have had. The customer service team was incredibly helpful when I had a question about my order.', name: 'Mike C.', role: 'Verified Buyer', image: 'https://i.pinimg.com/736x/52/62/6f/52626f71391e476ab27e76422c2b5929.jpg' },
        { text: 'Great prices and excellent product selection. The easy return policy gave me confidence to try new products without worry.', name: 'Emma D.', role: 'Verified Buyer', image: 'https://i.pinimg.com/1200x/61/65/e3/6165e3b3b4d7d22d29de95eab429930d.jpg' },
      ],
    }
  },
  async mounted() {
    await Promise.all([this.fetchFeaturedProducts(), this.fetchCategories(), this.fetchStats()])
  },
  methods: {
    starFill,
    async fetchStats() {
      try {
        const s = await get('/api/stats')
        this.stats = {
          products: s.products ?? 0,
          customers: s.customers ?? 0,
          rating: s.reviews_count > 0 ? s.rating + '★' : 'New',
          reviewsCount: s.reviews_count ?? 0,
        }
      } catch { /* keep placeholders */ }
    },
    async fetchFeaturedProducts() {
      this.loadingProducts = true
      try {
        const data = await get('/api/products', { per_page: 8 })
        this.featuredProducts = data.data || data || []
      } catch { this.featuredProducts = [] }
      finally { this.loadingProducts = false }
    },
    async fetchCategories() {
      this.categoriesLoading = true
      try {
        const data = await get('/api/categories')
        this.categories = (data.data || data || []).slice(0, 6)
      } catch { this.categories = [] }
      finally { this.categoriesLoading = false }
    },
    async addToCart(product) {
      if (this.addingId) return
      this.addingId = product.id
      try {
        await post('/api/cart', { product_id: product.id, quantity: 1 })
        this.$refs.successModal.show('"' + product.name + '" has been added to your cart.')
      } catch (err) {
        this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: err.data?.message || 'Failed to add to cart.' })
      } finally { this.addingId = null }
    },
  },
}
</script>
