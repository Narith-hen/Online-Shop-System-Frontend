<template>
  <div>
    <section class="relative text-white py-24 px-4" style="background-image: url('https://hips.hearstapps.com/hmg-prod/images/tech-gifts-6939ea8891e6c.jpg?crop=0.8930232558139535xw:1xh;center,top&resize=1200:*'); background-size: cover; background-position: center;">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
      <div class="max-w-7xl mx-auto animate-fade-in-down relative text-center">
        <h1 class="text-5xl font-extrabold mb-3 drop-shadow-lg">My Wishlist</h1>
        <p class="text-xl text-white/80 font-light max-w-xl mx-auto">Products you love, saved for later</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-12">
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="i in 8" :key="i" class="bg-white rounded-xl overflow-hidden shadow-sm">
          <div class="skeleton skeleton-image h-48"></div>
          <div class="p-4 space-y-3">
            <div class="skeleton skeleton-text-sm w-1/3"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text-sm w-2/3"></div>
            <div class="flex justify-between items-center pt-2">
              <div class="skeleton skeleton-text-lg w-1/3"></div>
              <div class="skeleton skeleton-button"></div>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="items.length === 0" class="text-center py-20 animate-fade-in">
        <div class="inline-flex items-center justify-center w-28 h-28 bg-gradient-to-br from-pink-100 to-rose-100 rounded-full mb-6">
          <i class="fas fa-heart-crack text-5xl text-pink-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Your wishlist is empty</h3>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">
          Save your favorite products by clicking the heart icon on any product page. They'll appear here for easy access later.
        </p>
        <router-link to="/products" class="btn-primary text-base py-3 px-8">
          <i class="fas fa-arrow-left mr-1"></i> Browse Products
        </router-link>
      </div>

      <div v-else>
        <div class="flex items-center justify-between mb-6">
          <p class="text-sm text-gray-500">{{ pagination.total }} {{ pagination.total === 1 ? 'item' : 'items' }} in your wishlist</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="(item, index) in items" :key="item.id"
            class="product-card animate-fade-in-up" :style="{ animationDelay: `${index * 60}ms` }">
            <div class="relative">
              <router-link :to="`/products/${item.product.id}`" class="block overflow-hidden">
                <img :src="item.product.image_url || 'https://placehold.co/300x200/e2e8f0/64748b?text=No+Image'"
                  :alt="item.product.name" class="product-img w-full h-48 object-cover" />
              </router-link>
              <button @click="removeFromWishlist(item.product.id)"
                class="absolute top-3 right-3 w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-md hover:bg-white hover:scale-110 active:scale-95 transition"
                title="Remove from wishlist">
                <i class="fas fa-heart text-pink-500"></i>
              </button>
              <div v-if="item.product.stock === 0"
                class="absolute bottom-3 left-3 bg-red-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                Out of stock
              </div>
            </div>
            <div class="p-4">
              <p v-if="item.product.category" class="text-xs text-blue-500 font-semibold mb-1 uppercase tracking-wide">{{ item.product.category.name }}</p>
              <router-link :to="`/products/${item.product.id}`"
                class="font-bold text-base mb-1.5 block hover:text-blue-500 transition leading-snug">
                {{ item.product.name }}
              </router-link>
              <p v-if="item.product.description" class="text-gray-500 text-xs mb-3 line-clamp-2">{{ item.product.description }}</p>
              <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-blue-500">${{ Number(item.product.price).toFixed(2) }}</span>
                <button @click="addToCart(item.product)" :disabled="item.product.stock === 0 || addingId === item.product.id"
                  class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 active:scale-95 transition-all text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5">
                  <i v-if="addingId === item.product.id" class="fas fa-spinner fa-spin"></i>
                  <i v-else class="fas fa-cart-plus"></i>
                  {{ addingId === item.product.id ? 'Adding...' : 'Cart' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <div v-if="pagination.last_page > 1" class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 mt-8 border-t border-gray-100">
          <p class="text-sm text-gray-500">
            Page {{ pagination.current_page }} of {{ pagination.last_page }}
          </p>
          <div class="flex items-center gap-2">
            <button @click="goToPage(pagination.current_page - 1)"
              :disabled="pagination.current_page <= 1"
              class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
              <i class="fas fa-chevron-left mr-1"></i> Previous
            </button>
            <template v-for="page in pageNumbers" :key="page">
              <span v-if="page === '...'" class="px-2 text-gray-400">...</span>
              <button v-else @click="goToPage(page)"
                class="w-10 h-10 rounded-lg text-sm font-semibold transition"
                :class="page === pagination.current_page ? 'bg-pink-600 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-100'">
                {{ page }}
              </button>
            </template>
            <button @click="goToPage(pagination.current_page + 1)"
              :disabled="pagination.current_page >= pagination.last_page"
              class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
              Next <i class="fas fa-chevron-right ml-1"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { get, post } from '@/services/api'

export default {
  name: 'WishlistPage',
  data() {
    return {
      items: [],
      loading: true,
      addingId: null,
      pagination: { current_page: 1, last_page: 1, per_page: 12, total: 0 },
    }
  },
  computed: {
    pageNumbers() {
      const c = this.pagination.current_page, l = this.pagination.last_page
      if (l <= 3) return Array.from({ length: l }, (_, i) => i + 1)
      const start = Math.max(1, c - 1)
      const end = Math.min(l, c + 1)
      const pages = []
      if (start > 1) pages.push('...')
      for (let i = start; i <= end; i++) pages.push(i)
      if (end < l) pages.push('...')
      return pages
    },
  },
  async mounted() {
    await this.fetchWishlist()
  },
  methods: {
    async fetchWishlist(page) {
      this.loading = true
      try {
        const endpoint = page ? `/api/wishlist?page=${page}` : '/api/wishlist'
        const data = await get(endpoint)
        this.items = data.data || data.items || data || []
        this.pagination = {
          current_page: data.current_page || data.meta?.current_page || 1,
          last_page: data.last_page || data.meta?.last_page || 1,
          per_page: data.per_page || data.meta?.per_page || 12,
          total: data.total || data.meta?.total || this.items.length,
        }
      } catch (e) {
        console.error('Wishlist fetch error:', e)
        this.items = []
      } finally {
        this.loading = false
      }
    },
    async goToPage(page) {
      if (page < 1 || page > this.pagination.last_page) return
      await this.fetchWishlist(page)
      window.scrollTo({ top: 0, behavior: 'smooth' })
    },
    async removeFromWishlist(productId) {
      try {
        await post('/api/wishlist/toggle', { product_id: productId })
        this.items = this.items.filter(i => i.product_id !== productId && i.product?.id !== productId)
        this.pagination.total = Math.max(0, this.pagination.total - 1)
        if (this.items.length === 0 && this.pagination.current_page > 1) {
          await this.fetchWishlist(this.pagination.current_page - 1)
        }
      } catch (e) {
        console.error('Remove error:', e)
      }
    },
    async addToCart(product) {
      if (this.addingId) return
      this.addingId = product.id
      try {
        await post('/api/cart', { product_id: product.id, quantity: 1 })
      } catch (e) {
        console.error('Add to cart error:', e)
      } finally {
        this.addingId = null
      }
    },
  },
}
</script>
