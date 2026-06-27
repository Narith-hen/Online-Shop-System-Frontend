<template>
  <div>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <router-link to="/products" class="text-blue-600 hover:text-blue-800 mb-6 inline-block">
      &larr; Back to Products
    </router-link>

    <div v-if="loading" class="text-center py-12">
      <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
      <p class="text-gray-500">Loading product...</p>
    </div>

    <div v-else-if="!product" class="text-center py-16">
      <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
        <i class="fas fa-box-open text-4xl text-gray-400"></i>
      </div>
      <h3 class="text-xl font-bold text-gray-900 mb-2">Product Not Found</h3>
      <p class="text-gray-500 mb-6">The product you are looking for does not exist.</p>
      <router-link to="/products" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition">
        Browse Products
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <div class="animate-fade-in-left">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <img
            :src="product.image_url || 'https://via.placeholder.com/600x400'"
            :alt="product.name"
            class="w-full h-96 object-cover"
          />
        </div>
      </div>

      <div class="animate-fade-in-right">
        <span v-if="product.category" class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full mb-4">
          {{ product.category.name }}
        </span>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>

        <div class="flex items-center gap-1 mb-4">
          <span class="text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
          <span class="text-gray-600 text-sm">(128 reviews)</span>
        </div>

        <div class="mb-6">
          <span class="text-4xl font-bold text-blue-600">${{ product.price?.toFixed(2) }}</span>
        </div>

        <div class="mb-6">
          <h3 class="font-bold text-gray-900 mb-2">Description</h3>
          <p class="text-gray-600 leading-relaxed">
            {{ product.description || 'High quality product with great features. This product is carefully crafted to meet the highest standards of quality and performance. Perfect for everyday use.' }}
          </p>
        </div>

        <div class="mb-6">
          <h3 class="font-bold text-gray-900 mb-2">Availability</h3>
          <p v-if="product.stock > 0" class="text-green-600 font-semibold">In Stock ({{ product.stock }} available)</p>
          <p v-else class="text-red-600 font-semibold">Out of Stock</p>
        </div>

        <div class="flex gap-4 mt-8">
          <div class="flex items-center border border-gray-300 rounded-lg">
            <button @click="decrementQty" class="px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-l-lg">-</button>
            <span class="px-6 py-3 font-semibold">{{ quantity }}</span>
            <button @click="incrementQty" class="px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-r-lg">+</button>
          </div>
          <button
            @click="addToCart"
            :disabled="product.stock === 0 || addingToCart"
            class="flex-1 bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i v-if="addingToCart" class="fas fa-spinner fa-spin mr-1"></i>
            {{ addingToCart ? 'Adding...' : 'Add to Cart' }}
          </button>
          <button
            @click="toggleWishlist"
            :disabled="togglingWishlist"
            class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50"
            :class="{ 'text-red-500 border-red-300 bg-red-50': inWishlist }"
          >
            <i v-if="togglingWishlist" class="fas fa-spinner fa-spin"></i>
            <i v-else class="fas fa-heart"></i>
          </button>
        </div>

        <div class="mt-8 border-t pt-6">
          <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
              <span class="font-semibold text-gray-900">SKU:</span> {{ product.id }}
            </div>
            <div>
              <span class="font-semibold text-gray-900">Category:</span>
              <router-link v-if="product.category" :to="`/categories/${product.category.id}`" class="text-blue-600 hover:underline">
                {{ product.category.name }}
              </router-link>
              <span v-else>None</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <SuccessModal ref="successModal" />
  </div>
</template>

<script>
import SuccessModal from '@/components/SuccessModal.vue'

export default {
  name: 'ProductDetail',
  components: { SuccessModal },
  data() {
    return {
      product: null,
      loading: true,
      quantity: 1,
      addingToCart: false,
      togglingWishlist: false,
      inWishlist: false,
    }
  },
  async mounted() {
    await this.fetchProduct()
  },
  methods: {
    async fetchProduct() {
      this.loading = true
      try {
        const token = localStorage.getItem('token')
        const headers = {}
        if (token) {
          headers.Authorization = `Bearer ${token}`
        }
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/products/${this.$route.params.id}`, { headers })
        if (res.ok) {
          const data = await res.json()
          this.product = data.data || null
        }
      } catch {
        this.product = null
      } finally {
        this.loading = false
      }
    },
    incrementQty() {
      if (this.quantity < this.product.stock) {
        this.quantity++
      }
    },
    decrementQty() {
      if (this.quantity > 1) {
        this.quantity--
      }
    },
    async addToCart() {
      if (this.addingToCart || this.product.stock === 0) return
      this.addingToCart = true
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
            product_id: this.product.id,
            quantity: this.quantity,
          }),
        })
        if (res.ok) {
          this.$refs.successModal.show('You ordered product "' + this.product.name + '" successfully.')
        } else {
          const data = await res.json()
          alert(data.message || 'Failed to add to cart.')
        }
      } catch {
        alert('Failed to add to cart.')
      } finally {
        this.addingToCart = false
      }
    },
    async toggleWishlist() {
      if (this.togglingWishlist) return
      this.togglingWishlist = true
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/wishlist/toggle`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          body: JSON.stringify({ product_id: this.product.id }),
        })
        if (res.ok) {
          const data = await res.json()
          this.inWishlist = data.in_wishlist || false
        }
      } catch {
        // ignore
      } finally {
        this.togglingWishlist = false
      }
    },
  },
}
</script>
