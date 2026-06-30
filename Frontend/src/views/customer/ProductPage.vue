<template>
  <div>
  <div class="max-w-7xl mx-auto px-4 py-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
      <router-link to="/" class="hover:text-blue-500">Home</router-link>
      <i class="fas fa-chevron-right text-[10px]"></i>
      <span class="text-gray-900 font-medium">Products</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <div class="lg:col-span-1 animate-fade-in-left">
        <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-24">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900 text-sm">Filters</h3>
            <button v-if="searchQuery || selectedCategory || inStockOnly" @click="resetFilters" class="text-xs text-blue-500 hover:text-blue-600 font-medium">Clear all</button>
          </div>

          <div class="mb-5">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
              <input v-model="searchQuery" type="text" placeholder="Search..."
                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400 text-sm" />
            </div>
          </div>

          <div class="mb-5">
            <h4 class="font-semibold text-gray-900 text-xs uppercase tracking-wide mb-3">Category</h4>
            <div class="space-y-1">
              <button @click="selectedCategory = null"
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition flex items-center gap-2"
                :class="!selectedCategory ? 'bg-gray-900 text-white font-medium' : 'text-gray-600 hover:bg-gray-100'">
                <i class="fas fa-th-list text-xs"></i> All Categories
              </button>
              <button v-for="cat in categories" :key="cat.id" @click="selectedCategory = cat.id"
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition flex items-center justify-between gap-2"
                :class="selectedCategory === cat.id ? 'bg-gray-900 text-white font-medium' : 'text-gray-600 hover:bg-gray-100'">
                <span>{{ cat.name }}</span>
                <span class="text-xs opacity-60">({{ cat.products_count || 0 }})</span>
              </button>
            </div>
          </div>

          <div>
            <h4 class="font-semibold text-gray-900 text-xs uppercase tracking-wide mb-3">Availability</h4>
            <label class="flex items-center gap-2.5 cursor-pointer py-1">
              <input type="checkbox" v-model="inStockOnly"
                class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900 accent-gray-900" />
              <span class="text-sm text-gray-700">In Stock Only</span>
            </label>
          </div>
        </div>
      </div>

      <div class="lg:col-span-3 animate-fade-in-right">
        <div class="flex items-center justify-between mb-5">
          <p class="text-sm text-gray-500">
            {{ loading ? 'Loading...' : (totalProducts + ' result' + (totalProducts !== 1 ? 's' : '')) }}
          </p>
        </div>

        <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <div v-for="i in 6" :key="i" class="bg-white rounded-xl overflow-hidden border border-gray-200">
            <div class="skeleton skeleton-image h-48 !rounded-none"></div>
            <div class="p-4 space-y-3">
              <div class="skeleton skeleton-text-sm"></div>
              <div class="skeleton skeleton-text"></div>
              <div class="flex items-center gap-1"><span v-for="s in 5" :key="s" class="skeleton w-3 h-3 rounded-full inline-block"></span></div>
              <div class="flex justify-between items-center pt-2">
                <div class="skeleton skeleton-text-lg w-20"></div>
                <div class="skeleton skeleton-button w-24 h-8"></div>
              </div>
            </div>
          </div>
        </div>

        <div v-else-if="products.length === 0" class="text-center py-20 border border-dashed border-gray-300 rounded-xl">
          <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
            <i class="fas fa-box-open text-3xl text-gray-400"></i>
          </div>
          <h3 class="text-lg font-bold text-gray-900 mb-1">No products found</h3>
          <p class="text-sm text-gray-500 mb-5">Try a different search or category.</p>
          <button @click="resetFilters" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">Clear Filters</button>
        </div>

        <div v-else>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div v-for="(product, index) in products" :key="product.id"
              class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 animate-fade-in-up"
              :style="{ animationDelay: `${index * 50}ms` }">
              <router-link :to="`/products/${product.id}`" class="block bg-white p-4 flex items-center justify-center h-48 border-b border-gray-100">
                <img :src="product.image_url || 'https://placehold.co/200x180/e2e8f0/64748b?text=No+Image'" :alt="product.name"
                  class="w-full h-full object-contain" />
              </router-link>
              <div class="p-4">
                <p v-if="product.category" class="text-[11px] text-gray-400 mb-1">{{ product.category.name }}</p>
                <router-link :to="`/products/${product.id}`" class="text-sm font-medium text-gray-900 hover:text-blue-500 leading-snug block mb-2 line-clamp-2">
                  {{ product.name }}
                </router-link>
                <div class="flex items-center gap-1 mb-2">
                  <span v-for="s in 5" :key="s" class="text-[10px]" :class="s <= 4 ? 'text-yellow-400' : 'text-gray-200'"><i class="fas fa-star"></i></span>
                </div>
                <div v-if="product.stock <= 3 && product.stock > 0" class="text-[10px] text-amber-600 font-medium mb-2">Only {{ product.stock }} left</div>
                <div v-if="product.stock === 0" class="text-[10px] text-red-600 font-medium mb-2">Out of stock</div>
                <div class="flex items-center justify-between">
                  <span class="text-lg font-bold text-gray-900">${{ Number(product.price).toFixed(2) }}</span>
                  <button @click.stop="addToCart(product)" :disabled="product.stock === 0 || addingId === product.id"
                    class="px-4 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg hover:bg-gray-800 transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5">
                    <i v-if="addingId === product.id" class="fas fa-spinner fa-spin"></i>
                    <i v-else class="fas fa-cart-plus"></i>
                    {{ addingId === product.id ? '' : 'Add' }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div v-if="lastPage > 1" class="flex items-center justify-center gap-2 mt-10">
            <button @click="changePage(currentPage - 1)" :disabled="currentPage <= 1"
              class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-100 transition disabled:opacity-40 disabled:cursor-not-allowed">
              <i class="fas fa-chevron-left"></i>
            </button>
            <template v-for="p in visiblePages" :key="p">
              <span v-if="p === '...'" class="px-2 text-gray-400 text-sm">...</span>
              <button v-else @click="changePage(p)"
                class="w-9 h-9 rounded-lg text-sm font-medium transition"
                :class="p === currentPage ? 'bg-gray-900 text-white' : 'border border-gray-300 text-gray-600 hover:bg-gray-100'">
                {{ p }}
              </button>
            </template>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage >= lastPage"
              class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-100 transition disabled:opacity-40 disabled:cursor-not-allowed">
              <i class="fas fa-chevron-right"></i>
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
      products: [], categories: [], loading: true,
      searchQuery: '', selectedCategory: null, inStockOnly: false,
      addingId: null, currentPage: 1, lastPage: 1, totalProducts: 0,
    }
  },
  computed: {
    visiblePages() {
      const c = this.currentPage, l = this.lastPage
      if (l <= 7) return Array.from({ length: l }, (_, i) => i + 1)
      const p = [1]; if (c > 3) p.push('...')
      const s = Math.max(2, c - 1), e = Math.min(l - 1, c + 1)
      for (let i = s; i <= e; i++) p.push(i)
      if (c < l - 2) p.push('...'); if (l > 1) p.push(l)
      return p
    },
  },
  watch: {
    searchQuery() { this.currentPage = 1; this.fetchProducts() },
    selectedCategory() { this.currentPage = 1; this.fetchProducts() },
    inStockOnly() { this.currentPage = 1; this.fetchProducts() },
  },
  async mounted() { await Promise.all([this.fetchProducts(), this.fetchCategories()]) },
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
      } catch { this.products = [] }
      finally { this.loading = false }
    },
    changePage(p) { if (p < 1 || p > this.lastPage) return; this.currentPage = p; this.fetchProducts(); window.scrollTo({ top: 0, behavior: 'smooth' }) },
    async fetchCategories() { try { const d = await get('/api/categories'); this.categories = d.data || [] } catch { this.categories = [] } },
    async addToCart(product) { if (this.addingId) return; this.addingId = product.id; try { await post('/api/cart', { product_id: product.id, quantity: 1 }); this.$refs.successModal.show('"' + product.name + '" added.') } catch (e) { this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: e.data?.message || 'Failed.' }) } finally { this.addingId = null } },
    resetFilters() { this.searchQuery = ''; this.selectedCategory = null; this.inStockOnly = false; this.currentPage = 1; this.fetchProducts() },
  },
}
</script>
