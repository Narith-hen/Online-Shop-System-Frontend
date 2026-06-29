<template>
  <div>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 animate-fade-in-down">Our Products</h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Sidebar Filters -->
      <div class="lg:col-span-1 animate-fade-in-left">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sticky top-24">
          <div class="mb-6">
            <h3 class="font-bold text-sm text-gray-900 mb-3 uppercase tracking-wide">Search</h3>
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
              <input v-model="searchQuery" type="text" placeholder="Search products..."
                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400 text-sm" />
            </div>
          </div>

          <div class="mb-6">
            <h3 class="font-bold text-sm text-gray-900 mb-3 uppercase tracking-wide">Categories</h3>
            <div class="space-y-1.5">
              <button @click="selectedCategory = null"
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition"
                :class="!selectedCategory ? 'bg-blue-200 text-blue-500 font-semibold' : 'text-gray-600 hover:bg-gray-50'">
                <i class="fas fa-th-list mr-2 text-xs"></i>All Categories
              </button>
              <button v-for="cat in categories" :key="cat.id" @click="selectedCategory = cat.id"
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition"
                :class="selectedCategory === cat.id ? 'bg-blue-200 text-blue-500 font-semibold' : 'text-gray-600 hover:bg-gray-50'">
                {{ cat.name }}
                <span class="text-xs text-gray-400 ml-1">({{ cat.products_count || 0 }})</span>
              </button>
            </div>
          </div>

          <div>
            <h3 class="font-bold text-sm text-gray-900 mb-3 uppercase tracking-wide">Availability</h3>
            <label class="flex items-center gap-2.5 cursor-pointer px-1 py-1.5">
              <input type="checkbox" v-model="inStockOnly"
                class="w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-400" />
              <span class="text-sm text-gray-700">In Stock Only</span>
            </label>
          </div>

          <button @click="resetFilters" v-if="searchQuery || selectedCategory || inStockOnly"
            class="mt-5 w-full py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition border border-red-200">
            <i class="fas fa-times mr-1"></i> Clear Filters
          </button>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="lg:col-span-3 animate-fade-in-right">
        <!-- Skeleton -->
        <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="i in 6" :key="i" class="bg-white rounded-xl overflow-hidden border border-gray-100">
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

        <!-- Empty -->
        <div v-else-if="products.length === 0" class="text-center py-20">
          <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
            <i class="fas fa-box-open text-4xl text-gray-400"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
          <p class="text-gray-500 mb-6">Try adjusting your search or filters.</p>
          <button @click="resetFilters" class="btn-primary">Reset Filters</button>
        </div>

        <div v-else>
          <div class="mb-5 flex justify-between items-center">
            <p class="text-sm text-gray-500">Showing <span class="font-semibold text-gray-900">{{ products.length }}</span> of <span class="font-semibold text-gray-900">{{ totalProducts }}</span> products</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="(product, index) in products" :key="product.id"
              class="product-card animate-fade-in-up" :style="{ animationDelay: `${index * 60}ms` }">
              <router-link :to="`/products/${product.id}`" class="block overflow-hidden">
                <img :src="product.image_url || 'https://via.placeholder.com/300x200?text=No+Image'" :alt="product.name"
                  class="product-img w-full h-48 object-cover" />
              </router-link>
              <div class="p-4">
                <div class="flex items-start justify-between mb-1.5">
                  <p v-if="product.category" class="text-xs text-blue-500 font-semibold uppercase tracking-wide">{{ product.category.name }}</p>
                  <span v-if="product.stock <= 5 && product.stock > 0" class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">Low stock</span>
                  <span v-if="product.stock === 0" class="text-[10px] font-semibold text-red-600 bg-red-50 px-1.5 py-0.5 rounded">Out of stock</span>
                </div>
                <router-link :to="`/products/${product.id}`" class="font-bold text-base mb-1.5 block hover:text-blue-500 transition leading-snug">
                  {{ product.name }}
                </router-link>
                <p class="text-gray-500 text-xs mb-3 line-clamp-2">{{ product.description || 'No description.' }}</p>
                <div class="flex justify-between items-center">
                  <span class="text-xl font-bold text-blue-500">${{ product.price?.toFixed(2) }}</span>
                  <button @click.stop="addToCart(product)" :disabled="product.stock === 0 || addingId === product.id"
                    class="bg-blue-500 text-white px-3 py-1.5 rounded-lg hover:bg-blue-600 transition text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    <i v-if="addingId === product.id" class="fas fa-spinner fa-spin mr-1"></i>
                    {{ addingId === product.id ? 'Adding...' : 'Add to Cart' }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="lastPage > 1" class="flex items-center justify-center gap-3 mt-10">
            <button @click="changePage(currentPage - 1)" :disabled="currentPage <= 1"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed transition">
              <i class="fas fa-chevron-left mr-1"></i> Previous
            </button>
            <div class="flex items-center gap-1.5">
              <span v-for="p in visiblePages" :key="p" @click="changePage(p)"
                class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-medium cursor-pointer transition"
                :class="p === currentPage ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100'">
                {{ p }}
              </span>
            </div>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage >= lastPage"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed transition">
              Next <i class="fas fa-chevron-right ml-1"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <SuccessModal ref="successModal" />
    <Toast ref="toastRef" />
  </div>
  </div>
</template>

<script>
import SuccessModal from '@/components/SuccessModal.vue'
import Toast from '@/components/Toast.vue'
import { get, post } from '@/services/api'

export default {
  name: 'CustomerProducts',
  components: { SuccessModal, Toast },
  data() {
    return {
      products: [],
      categories: [],
      loading: true,
      searchQuery: '',
      selectedCategory: null,
      inStockOnly: false,
      addingId: null,
      currentPage: 1,
      lastPage: 1,
      totalProducts: 0,
    }
  },
  computed: {
    visiblePages() {
      const p = []
      const start = Math.max(1, this.currentPage - 2)
      const end = Math.min(this.lastPage, start + 4)
      for (let i = start; i <= end; i++) p.push(i)
      return p
    },
  },
  watch: {
    searchQuery() { this.currentPage = 1; this.fetchProducts() },
    selectedCategory() { this.currentPage = 1; this.fetchProducts() },
    inStockOnly() { this.currentPage = 1; this.fetchProducts() },
  },
  async mounted() {
    await Promise.all([this.fetchProducts(), this.fetchCategories()])
  },
  methods: {
    async fetchProducts() {
      this.loading = true
      try {
        const params = { page: this.currentPage, per_page: 12 }
        if (this.searchQuery) params.search = this.searchQuery
        if (this.selectedCategory) params.category_id = this.selectedCategory
        if (this.inStockOnly) params.in_stock = '1'
        const data = await get('/api/products', params)
        this.products = data.data || []
        this.currentPage = data.meta?.current_page || 1
        this.lastPage = data.meta?.last_page || 1
        this.totalProducts = data.meta?.total || 0
      } catch {
        this.products = []
      } finally {
        this.loading = false
      }
    },
    changePage(page) {
      if (page < 1 || page > this.lastPage) return
      this.currentPage = page
      this.fetchProducts()
      window.scrollTo({ top: 0, behavior: 'smooth' })
    },
    async fetchCategories() {
      try {
        const data = await get('/api/categories')
        this.categories = data.data || []
      } catch {
        this.categories = []
      }
    },
    async addToCart(product) {
      if (this.addingId) return
      this.addingId = product.id
      try {
        await post('/api/cart', { product_id: product.id, quantity: 1 })
        this.$refs.successModal.show('"' + product.name + '" has been added to your cart.')
      } catch (err) {
        this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: err.data?.message || 'Failed to add to cart.' })
      } finally {
        this.addingId = null
      }
    },
    resetFilters() {
      this.searchQuery = ''
      this.selectedCategory = null
      this.inStockOnly = false
      this.currentPage = 1
      this.fetchProducts()
    },
  },
}
</script>
