<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 animate-fade-in-down">Our Products</h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Sidebar Filters -->
      <div class="lg:col-span-1 animate-fade-in-left">
        <div class="bg-white rounded-lg shadow-md p-6">
          <!-- Search -->
          <div class="mb-6">
            <h3 class="font-bold text-lg mb-3">Search</h3>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search products..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
            />
          </div>

          <!-- Categories -->
          <div class="mb-6">
            <h3 class="font-bold text-lg mb-3">Categories</h3>
            <div class="space-y-2">
              <label
                v-for="category in categories"
                :key="category.id"
                class="flex items-center cursor-pointer"
              >
                <input
                  type="radio"
                  name="category"
                  :value="category.id"
                  v-model="selectedCategory"
                  class="mr-2"
                />
                <span class="text-sm">{{ category.name }}</span>
                <span class="text-xs text-gray-400 ml-1">({{ category.products_count || 0 }})</span>
              </label>
              <label v-if="categories.length" class="flex items-center cursor-pointer mt-1">
                <input
                  type="radio"
                  name="category"
                  :value="null"
                  v-model="selectedCategory"
                  class="mr-2"
                />
                <span class="text-sm text-blue-600">All Categories</span>
              </label>
            </div>
          </div>

          <!-- Availability Filter -->
          <div>
            <h3 class="font-bold text-lg mb-3">Availability</h3>
            <div class="space-y-2">
              <label class="flex items-center cursor-pointer">
                <input
                  type="checkbox"
                  v-model="inStockOnly"
                  class="mr-2"
                />
                <span class="text-sm">In Stock Only</span>
              </label>
            </div>
          </div>
        </div>
      </div>

        <!-- Products Grid -->
      <div class="lg:col-span-3 animate-fade-in-right">
        <!-- Loading -->
        <div v-if="loading" class="text-center py-16">
          <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
          <p class="text-gray-500">Loading products...</p>
        </div>

        <!-- Empty -->
        <div v-else-if="products.length === 0" class="text-center py-16">
          <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
            <i class="fas fa-box-open text-4xl text-gray-400"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
          <p class="text-gray-500 mb-6">Try adjusting your search or filters.</p>
          <button
            @click="resetFilters"
            class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition"
          >
            Reset Filters
          </button>
        </div>

        <!-- Product Grid -->
        <div v-else>
          <div class="mb-6 flex justify-between items-center">
            <p class="text-gray-600">Showing {{ products.length }} of {{ totalProducts }} products</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="(product, index) in products"
              :key="product.id"
              class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg hover:-translate-y-1 transition animate-fade-in-up"
              :style="{ animationDelay: `${index * 60}ms` }"
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
                  class="font-bold text-lg mb-1 block hover:text-blue-600 transition"
                >
                  {{ product.name }}
                </router-link>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                  {{ product.description || 'No description available.' }}
                </p>
                <p class="text-sm mb-3">
                  <span v-if="product.stock > 0" class="text-green-600 font-medium">
                    {{ product.stock }} in stock
                  </span>
                  <span v-else class="text-red-600 font-medium">Out of stock</span>
                </p>
                <div class="flex justify-between items-center">
                  <span class="text-2xl font-bold text-blue-600">
                    ${{ product.price?.toFixed(2) }}
                  </span>
                  <button
                    @click.stop="addToCart(product)"
                    :disabled="product.stock === 0 || addingId === product.id"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                  >
                    <i v-if="addingId === product.id" class="fas fa-spinner fa-spin mr-1"></i>
                    {{ addingId === product.id ? 'Adding...' : 'Add to Cart' }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="lastPage > 1" class="flex justify-center items-center gap-2 mt-8">
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage <= 1"
              class="px-4 py-2 border rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Previous
            </button>
            <span class="text-sm text-gray-600">
              Page {{ currentPage }} of {{ lastPage }}
            </span>
            <button
              @click="changePage(currentPage + 1)"
              :disabled="currentPage >= lastPage"
              class="px-4 py-2 border rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Products',
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
        const token = localStorage.getItem('token')
        const headers = {}
        if (token) headers.Authorization = `Bearer ${token}`
        const params = new URLSearchParams({
          page: this.currentPage,
          per_page: 12,
        })
        if (this.searchQuery) params.append('search', this.searchQuery)
        if (this.selectedCategory) params.append('category_id', this.selectedCategory)
        if (this.inStockOnly) params.append('in_stock', '1')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/products?${params}`, { headers })
        if (res.ok) {
          const data = await res.json()
          this.products = data.data || []
          this.currentPage = data.meta?.current_page || 1
          this.lastPage = data.meta?.last_page || 1
          this.totalProducts = data.meta?.total || 0
        }
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
    },
    async fetchCategories() {
      try {
        const token = localStorage.getItem('token')
        const headers = {}
        if (token) headers.Authorization = `Bearer ${token}`
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/categories`, { headers })
        if (res.ok) {
          const data = await res.json()
          this.categories = data.data || []
        }
      } catch {
        this.categories = []
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
          },
          body: JSON.stringify({
            product_id: product.id,
            quantity: 1,
          }),
        })
        if (res.ok) {
          alert('Added to cart!')
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
