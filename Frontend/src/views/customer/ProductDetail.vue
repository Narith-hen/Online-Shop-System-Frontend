<template>
  <div>
  <div class="max-w-7xl mx-auto px-4 py-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
      <router-link to="/" class="hover:text-blue-500">Home</router-link>
      <i class="fas fa-chevron-right text-[10px]"></i>
      <router-link to="/products" class="hover:text-blue-500">Products</router-link>
      <i class="fas fa-chevron-right text-[10px]"></i>
      <span v-if="product?.category" class="text-gray-400">{{ product.category.name }}</span>
      <i v-if="product?.category" class="fas fa-chevron-right text-[10px]"></i>
      <span class="text-gray-900 font-medium truncate">{{ product?.name }}</span>
    </nav>

    <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-2 gap-10">
      <div><div class="skeleton skeleton-image h-[500px] rounded-xl"></div></div>
      <div class="space-y-4">
        <div class="skeleton skeleton-text-sm w-24"></div>
        <div class="skeleton skeleton-text-lg w-3/4"></div>
        <div class="skeleton skeleton-text-sm w-1/3"></div>
        <hr class="border-gray-200" />
        <div class="skeleton skeleton-text-lg w-1/4"></div>
        <div class="space-y-2">
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text w-2/3"></div>
        </div>
        <div class="skeleton skeleton-text-sm w-1/4"></div>
        <div class="flex gap-3 pt-4">
          <div class="skeleton skeleton-button w-32 h-12"></div>
          <div class="skeleton skeleton-button w-12 h-12 rounded-full"></div>
        </div>
      </div>
    </div>

    <div v-else-if="!product" class="text-center py-20 animate-fade-in">
      <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
        <i class="fas fa-box-open text-4xl text-gray-400"></i>
      </div>
      <h3 class="text-xl font-bold text-gray-900 mb-2">Product Not Found</h3>
      <p class="text-gray-500 mb-6">The product you are looking for does not exist.</p>
      <router-link to="/products" class="btn-primary text-base py-3 px-8">Browse Products</router-link>
    </div>

    <div v-else>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
        <div class="sticky top-24 self-start animate-fade-in-left">
          <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 flex items-center justify-center p-8">
              <img :src="product.image_url || 'https://placehold.co/600x500/e2e8f0/64748b?text=No+Image'"
                :alt="product.name" class="w-full max-h-[450px] object-contain" />
            </div>
          </div>
        </div>

        <div class="animate-fade-in-right">
          <p v-if="product.category" class="text-sm text-blue-500 font-medium mb-2">{{ product.category.name }}</p>
          <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight mb-3">{{ product.name }}</h1>

          <div class="flex items-center gap-1.5 mb-4">
            <template v-if="product.reviews_count">
              <div class="flex items-center gap-0.5">
                <span v-for="s in 5" :key="s" class="text-sm" :class="s <= starFill(product.reviews_avg_rating) ? 'text-yellow-400' : 'text-gray-200'"><i class="fas fa-star"></i></span>
              </div>
              <span class="text-sm text-gray-500">{{ product.reviews_avg_rating }} ({{ product.reviews_count }} review{{ product.reviews_count === 1 ? '' : 's' }})</span>
            </template>
            <span v-else class="text-sm text-gray-400">No reviews yet</span>
          </div>

          <hr class="border-gray-200 mb-4" />

          <div class="mb-4">
            <span class="text-3xl font-bold text-gray-900">${{ Number(product.price).toFixed(2) }}</span>
          </div>

          <div class="mb-5">
            <p class="text-gray-700 leading-relaxed text-sm">{{ product.description || 'No description available.' }}</p>
          </div>

          <div class="mb-5 flex items-center gap-2">
            <div v-if="product.stock > 0" class="flex items-center gap-1.5 text-emerald-600 font-medium text-sm">
              <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> In Stock
            </div>
            <div v-else class="flex items-center gap-1.5 text-red-600 font-medium text-sm">
              <span class="w-2 h-2 bg-red-500 rounded-full"></span> Out of Stock
            </div>
            <span class="text-gray-400 text-sm">({{ product.stock }} available)</span>
          </div>

          <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
              <button @click="decrementQty" :disabled="product.stock === 0"
                class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition disabled:opacity-30 text-lg">−</button>
              <span class="w-12 h-10 flex items-center justify-center font-semibold text-sm border-x border-gray-300 bg-white">{{ quantity }}</span>
              <button @click="incrementQty" :disabled="quantity >= product.stock"
                class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition disabled:opacity-30 text-lg">+</button>
            </div>
            <button @click="addToCart" :disabled="product.stock === 0 || addingToCart"
              class="flex-1 bg-blue-500 text-white font-bold py-2.5 px-8 rounded-lg hover:bg-blue-600 active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm">
              <i v-if="addingToCart" class="fas fa-spinner fa-spin mr-2"></i>
              <i v-else class="fas fa-cart-plus mr-2"></i>
              {{ addingToCart ? 'Adding...' : 'Add to Cart' }}
            </button>
            <button @click="toggleWishlist" :disabled="togglingWishlist"
              class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50"
              :class="{ 'text-red-500 border-red-300 bg-red-50': inWishlist }">
              <i v-if="togglingWishlist" class="fas fa-spinner fa-spin"></i>
              <i v-else :class="inWishlist ? 'fas fa-heart' : 'far fa-heart'"></i>
            </button>
          </div>

          <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-sm border border-gray-200">
            <div class="flex justify-between py-1"><span class="text-gray-500">SKU</span><span class="font-medium text-gray-900">#{{ product.id }}</span></div>
            <div class="flex justify-between py-1"><span class="text-gray-500">Category</span><router-link v-if="product.category" :to="`/categories/${product.category.id}`" class="text-blue-500 hover:underline font-medium">{{ product.category.name }}</router-link></div>
            <div class="flex justify-between py-1"><span class="text-gray-500">Stock</span><span :class="product.stock > 0 ? 'text-emerald-600' : 'text-red-600'" class="font-medium">{{ product.stock > 0 ? product.stock + ' units' : 'Out of stock' }}</span></div>
          </div>
        </div>
      </div>

    </div>
    <Toast ref="toastRef" />
  </div>
  </div>
</template>

<script>
import Toast from '@/components/Toast.vue'
import { get, post } from '@/services/api'
import { starFill } from '@/utils/rating'

export default {
  name: 'ProductDetail',
  components: { Toast },
  data() {
    return {
      product: null, loading: true, quantity: 1,
      addingToCart: false, togglingWishlist: false, inWishlist: false,
    }
  },
  async mounted() { await this.fetchProduct() },
  methods: {
    starFill,
    async fetchProduct() {
      this.loading = true
      try {
        const d = await get(`/api/products/${this.$route.params.id}`)
        this.product = d.data || null
        if (this.product) await this.checkWishlist()
      } catch { this.product = null }
      finally { this.loading = false }
    },
    async checkWishlist() {
      try {
        const w = await get('/api/wishlist')
        const items = w.items || []
        this.inWishlist = items.some(i => i.product_id === this.product.id || i.product?.id === this.product.id)
      } catch { this.inWishlist = false }
    },
    incrementQty() { if (this.quantity < this.product.stock) this.quantity++ },
    decrementQty() { if (this.quantity > 1) this.quantity-- },
    async addToCart() { if (this.addingToCart || this.product.stock === 0) return; this.addingToCart = true; try { await post('/api/cart', { product_id: this.product.id, quantity: this.quantity }); this.$refs.toastRef?.show({ type: 'success', title: 'Added to Cart', message: '"' + this.product.name + '" has been added to your cart.' }) } catch (e) { this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: e.data?.message || 'Failed.' }) } finally { this.addingToCart = false } },
    async toggleWishlist() { if (this.togglingWishlist) return; this.togglingWishlist = true; try { const d = await post('/api/wishlist/toggle', { product_id: this.product.id }); this.inWishlist = d.in_wishlist || false; this.$refs.toastRef?.show({ type: 'success', title: this.inWishlist ? 'Added to Wishlist' : 'Removed from Wishlist', message: this.inWishlist ? 'Saved!' : 'Removed.' }) } catch {} finally { this.togglingWishlist = false } },
  },
}
</script>
