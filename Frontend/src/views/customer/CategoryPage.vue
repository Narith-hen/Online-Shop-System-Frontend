<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <div v-if="loading" class="text-center py-12">
      <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
      <p class="text-gray-500">Loading...</p>
    </div>

    <template v-else-if="selectedCategory">
      <button @click="selectedCategory = null" class="text-blue-500 hover:text-blue-600 mb-6 inline-block">
        &larr; Back to Categories
      </button>

      <h1 class="text-3xl font-bold mb-2 animate-fade-in-down">{{ selectedCategory.name }}</h1>
      <p class="text-gray-600 mb-8">{{ selectedCategory.description || 'Browse products in this category.' }}</p>

      <div v-if="productsLoading" class="text-center py-12">
        <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
        <p class="text-gray-500">Loading products...</p>
      </div>

      <div v-else-if="products.length === 0" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
          <i class="fas fa-box-open text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No Products Found</h3>
        <p class="text-gray-500 mb-6">This category has no products yet.</p>
      </div>

      <div v-else>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="(product, index) in products"
            :key="product.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg hover:-translate-y-1 transition animate-fade-in-up"
            :style="{ animationDelay: `${index * 60}ms` }"
          >
            <router-link :to="`/products/${product.id}`">
              <img :src="product.image_url || 'https://placehold.co/300x200/e2e8f0/64748b?text=No+Image'" :alt="product.name" class="w-full h-48 object-cover" />
            </router-link>
            <div class="p-4">
              <router-link :to="`/products/${product.id}`" class="font-bold text-lg mb-2 block hover:text-blue-500">
                {{ product.name }}
              </router-link>
              <p class="text-gray-600 text-sm mb-4">{{ product.description || 'No description available.' }}</p>
              <div class="flex justify-between items-center">
                <span class="text-2xl font-bold text-blue-500">${{ product.price?.toFixed(2) }}</span>
                <button @click="addToCart(product)" :disabled="product.stock === 0 || addingId === product.id"
                  class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                  <i v-if="addingId === product.id" class="fas fa-spinner fa-spin"></i>
                  <span v-else>Add</span>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div v-if="productLastPage > 1" class="flex justify-center items-center gap-2 mt-8">
          <button
            @click="changeProductPage(productCurrentPage - 1)"
            :disabled="productCurrentPage <= 1"
            class="px-4 py-2 border rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <span class="text-sm text-gray-600">
            Page {{ productCurrentPage }} of {{ productLastPage }}
          </span>
          <button
            @click="changeProductPage(productCurrentPage + 1)"
            :disabled="productCurrentPage >= productLastPage"
            class="px-4 py-2 border rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>
    </template>

    <template v-else>
      <h1 class="text-4xl font-bold mb-8 animate-fade-in-down">Categories</h1>

      <div v-if="categories.length === 0" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
          <i class="fas fa-tags text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No Categories Found</h3>
        <p class="text-gray-500">There are no categories available yet.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="(category, index) in categories"
          :key="category.id"
          @click="selectCategory(category)"
          class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition border border-gray-200 hover:border-blue-500 animate-fade-in-up"
          :style="{ animationDelay: `${index * 80}ms` }"
        >
          <h3 class="text-xl font-bold text-gray-900 mb-2">{{ category.name }}</h3>
          <p class="text-gray-600 text-sm mb-4">{{ category.description || 'No description available.' }}</p>
          <div class="flex justify-between items-center">
            <span class="text-sm text-blue-500 font-semibold">{{ category.products_count || 0 }} products</span>
            <span class="text-blue-500 hover:text-blue-600 font-semibold">View Products &rarr;</span>
          </div>
        </div>
      </div>
    </template>
    <Toast ref="toastRef" />
  </div>
</template>

<script>
import Toast from '@/components/Toast.vue'
import { post } from '@/services/api'

export default {
  name: 'CategoryPage',
  components: { Toast },
  data() {
    return {
      categories: [],
      products: [],
      selectedCategory: null,
      loading: true,
      productsLoading: false,
      productCurrentPage: 1,
      productLastPage: 1,
      addingId: null,
    }
  },
  async mounted() {
    await this.fetchCategories()
    const categoryId = this.$route.params.id
    if (categoryId) {
      await this.loadCategoryDetail(categoryId)
    }
  },
  async beforeRouteUpdate(to, from, next) {
    if (to.params.id) {
      await this.loadCategoryDetail(to.params.id)
    } else {
      this.selectedCategory = null
      this.products = []
    }
    next()
  },
  methods: {
    async fetchCategories() {
      this.loading = true
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
      } finally {
        this.loading = false
      }
    },
    async selectCategory(category) {
      this.selectedCategory = category
      this.productCurrentPage = 1
      this.$router.push(`/categories/${category.id}`)
      await this.loadCategoryDetail(category.id)
    },
    async loadCategoryDetail(id, page = this.productCurrentPage) {
      this.productsLoading = true
      try {
        const token = localStorage.getItem('token')
        const headers = {}
        if (token) headers.Authorization = `Bearer ${token}`
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/categories/${id}?page=${page}&per_page=12`, { headers })
        if (res.ok) {
          const data = await res.json()
          this.selectedCategory = data.data
          this.products = data.data.products?.data || []
          this.productCurrentPage = data.data.products?.current_page || 1
          this.productLastPage = data.data.products?.last_page || 1
        }
      } catch {
        this.products = []
      } finally {
        this.productsLoading = false
      }
    },
    changeProductPage(page) {
      if (page < 1 || page > this.productLastPage) return
      this.productCurrentPage = page
      this.loadCategoryDetail(this.selectedCategory.id, page)
    },
    async addToCart(product) {
      if (this.addingId) return
      this.addingId = product.id
      try {
        await post('/api/cart', { product_id: product.id, quantity: 1 })
        this.$refs.toastRef?.show({ type: 'success', title: 'Added to Cart', message: '"' + product.name + '" has been added to your cart.' })
      } catch (err) {
        this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: err.data?.message || 'Failed to add to cart.' })
      } finally {
        this.addingId = null
      }
    },
  },
}
</script>
