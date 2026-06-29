<template>
  <div>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <router-link to="/products" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-blue-500 transition mb-6">
      <i class="fas fa-arrow-left"></i> Back to Products
    </router-link>

    <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <div class="animate-fade-in-left">
        <div class="skeleton skeleton-image h-96 rounded-xl"></div>
      </div>
      <div class="animate-fade-in-right space-y-4">
        <div class="skeleton skeleton-badge"></div>
        <div class="skeleton skeleton-text-lg w-3/4"></div>
        <div class="skeleton skeleton-text-sm w-1/3"></div>
        <div class="skeleton skeleton-text-lg w-1/4"></div>
        <div class="space-y-2">
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text w-2/3"></div>
        </div>
        <div class="skeleton skeleton-text-sm w-1/4"></div>
        <div class="flex gap-3 pt-4">
          <div class="skeleton skeleton-button w-28 h-11"></div>
          <div class="skeleton skeleton-button w-32 h-11"></div>
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
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <div class="animate-fade-in-left">
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 flex items-center justify-center h-96">
              <img :src="product.image_url || 'https://placehold.co/600x400/e2e8f0/64748b?text=No+Image'"
                :alt="product.name" class="w-full h-full object-cover" />
            </div>
          </div>
        </div>

        <div class="animate-fade-in-right">
          <span v-if="product.category" class="inline-block bg-blue-200 text-blue-500 text-sm font-semibold px-3 py-1 rounded-full mb-4 border border-blue-300">
            {{ product.category.name }}
          </span>
          <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>

          <div class="flex items-center gap-1 mb-4">
            <span v-for="s in 5" :key="s" class="text-yellow-400 text-sm"><i class="fas fa-star"></i></span>
            <span class="text-gray-500 text-sm ml-1">({{ reviews.length }} reviews)</span>
          </div>

          <div class="mb-6">
            <span class="text-4xl font-bold text-blue-500">${{ Number(product.price).toFixed(2) }}</span>
          </div>

          <div class="mb-6">
            <h3 class="font-bold text-gray-900 mb-2">Description</h3>
            <p class="text-gray-600 leading-relaxed">{{ product.description || 'No description available.' }}</p>
          </div>

          <div class="mb-6">
            <h3 class="font-bold text-gray-900 mb-2">Availability</h3>
            <p v-if="product.stock > 0" class="text-emerald-600 font-semibold flex items-center gap-1.5">
              <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> In Stock ({{ product.stock }} available)
            </p>
            <p v-else class="text-red-600 font-semibold flex items-center gap-1.5">
              <span class="w-2 h-2 bg-red-500 rounded-full"></span> Out of Stock
            </p>
          </div>

          <div class="flex gap-3 mt-8">
            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
              <button @click="decrementQty" :disabled="product.stock === 0"
                class="w-11 h-11 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition disabled:opacity-30 text-lg">−</button>
              <span class="w-12 h-11 flex items-center justify-center font-semibold border-x border-gray-300 bg-white">{{ quantity }}</span>
              <button @click="incrementQty" :disabled="quantity >= product.stock"
                class="w-11 h-11 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition disabled:opacity-30 text-lg">+</button>
            </div>
            <button @click="addToCart" :disabled="product.stock === 0 || addingToCart"
              class="flex-1 bg-blue-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
              <i v-if="addingToCart" class="fas fa-spinner fa-spin mr-1"></i>
              <i v-else class="fas fa-cart-plus mr-1"></i>
              {{ addingToCart ? 'Adding...' : 'Add to Cart' }}
            </button>
            <button @click="toggleWishlist" :disabled="togglingWishlist"
              class="w-11 h-11 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50"
              :class="{ 'text-red-500 border-red-300 bg-red-50': inWishlist }">
              <i v-if="togglingWishlist" class="fas fa-spinner fa-spin"></i>
              <i v-else :class="inWishlist ? 'fas fa-heart' : 'far fa-heart'"></i>
            </button>
          </div>

          <div class="mt-8 pt-6 border-t border-gray-100">
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
              <div><span class="font-semibold text-gray-900">SKU:</span> {{ product.id }}</div>
              <div>
                <span class="font-semibold text-gray-900">Category:</span>
                <router-link v-if="product.category" :to="`/categories/${product.category.id}`" class="text-blue-500 hover:underline ml-1">
                  {{ product.category.name }}
                </router-link>
                <span v-else class="ml-1">None</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="border-t border-gray-200 pt-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
          <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Customer Reviews ({{ reviews.length }})</h2>

            <div v-if="reviewsLoading" class="space-y-4">
              <div v-for="i in 3" :key="i" class="bg-white border border-gray-100 rounded-xl p-5 flex gap-4">
                <div class="skeleton skeleton-image w-10 h-10 rounded-full shrink-0"></div>
                <div class="flex-1 space-y-2">
                  <div class="skeleton skeleton-text-sm w-1/4"></div>
                  <div class="skeleton skeleton-text-sm w-1/3"></div>
                  <div class="skeleton skeleton-text"></div>
                </div>
              </div>
            </div>

            <div v-else-if="reviews.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200 animate-fade-in">
              <div class="text-gray-300 mb-3"><i class="fas fa-star text-4xl"></i></div>
              <p class="text-gray-500 font-medium">No reviews yet</p>
              <p class="text-sm text-gray-400 mt-1">Be the first to review this product!</p>
            </div>

            <div v-else class="space-y-4">
              <div v-for="review in reviews" :key="review.id"
                class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow animate-fade-in-up">
                <div class="flex items-start justify-between mb-3">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                      {{ review.user?.name?.charAt(0).toUpperCase() || '?' }}
                    </div>
                    <div>
                      <p class="font-semibold text-gray-900 text-sm">{{ review.user?.name || 'Anonymous' }}</p>
                      <div class="flex items-center gap-1 mt-0.5">
                        <span v-for="s in 5" :key="s" class="text-xs" :class="s <= review.rating ? 'text-yellow-400' : 'text-gray-200'">
                          <i class="fas fa-star"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <span class="text-xs text-gray-400">{{ formatDate(review.created_at) }}</span>
                </div>
                <p v-if="review.comment" class="text-sm text-gray-600 leading-relaxed">{{ review.comment }}</p>
              </div>
            </div>
          </div>

          <div>
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm sticky top-24 animate-fade-in-right">
              <h3 class="text-lg font-bold text-gray-900 mb-4">Write a Review</h3>

              <div v-if="reviewSubmitted" class="text-center py-6 animate-scale-in">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-green-100 rounded-full mb-3">
                  <i class="fas fa-check text-2xl text-green-500"></i>
                </div>
                <p class="font-semibold text-green-700">Review submitted!</p>
              </div>

              <div v-else>
                <div v-if="reviewError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ reviewError }}</div>

                <form @submit.prevent="submitReview" class="space-y-4">
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                    <div class="flex items-center gap-1">
                      <button v-for="s in 5" :key="s" type="button" @click="newRating = s"
                        class="text-2xl transition hover:scale-110"
                        :class="s <= newRating ? 'text-yellow-400' : 'text-gray-200'">
                        <i class="fas fa-star"></i>
                      </button>
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Comment</label>
                    <textarea v-model="newComment" rows="4" placeholder="Share your thoughts about this product..."
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400 text-sm resize-none"></textarea>
                  </div>
                  <button type="submit" :disabled="submittingReview || !newRating"
                    class="w-full bg-blue-500 text-white font-bold py-2.5 rounded-lg hover:bg-blue-600 transition disabled:opacity-50">
                    <i v-if="submittingReview" class="fas fa-spinner fa-spin mr-2"></i>
                    {{ submittingReview ? 'Submitting...' : 'Submit Review' }}
                  </button>
                </form>
              </div>
            </div>
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

export default {
  name: 'ProductDetail',
  components: { Toast },
  data() {
    return {
      product: null, loading: true, quantity: 1,
      addingToCart: false, togglingWishlist: false, inWishlist: false,
      reviews: [], reviewsLoading: false,
      newRating: 0, newComment: '', submittingReview: false,
      reviewSubmitted: false, reviewError: '',
    }
  },
  async mounted() {
    await this.fetchProduct()
    this.fetchReviews()
  },
  methods: {
    async fetchProduct() {
      this.loading = true
      try {
        const data = await get(`/api/products/${this.$route.params.id}`)
        this.product = data.data || null
      } catch { this.product = null }
      finally { this.loading = false }
    },
    async fetchReviews() {
      this.reviewsLoading = true
      try {
        const data = await get(`/api/products/${this.$route.params.id}/reviews`)
        this.reviews = data.data || data || []
      } catch { this.reviews = [] }
      finally { this.reviewsLoading = false }
    },
    incrementQty() { if (this.quantity < this.product.stock) this.quantity++ },
    decrementQty() { if (this.quantity > 1) this.quantity-- },
    formatDate(date) {
      if (!date) return ''
      return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
    },
    async addToCart() {
      if (this.addingToCart || this.product.stock === 0) return
      this.addingToCart = true
      try {
        await post('/api/cart', { product_id: this.product.id, quantity: this.quantity })
        this.$refs.toastRef?.show({ type: 'success', title: 'Added to Cart', message: '"' + this.product.name + '" has been added to your cart.' })
      } catch (err) { this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: err.data?.message || 'Failed to add to cart.' }) }
      finally { this.addingToCart = false }
    },
    async toggleWishlist() {
      if (this.togglingWishlist) return
      this.togglingWishlist = true
      try {
        const data = await post('/api/wishlist/toggle', { product_id: this.product.id })
        this.inWishlist = data.in_wishlist || false
        this.$refs.toastRef?.show({ type: 'success', title: this.inWishlist ? 'Added to Wishlist' : 'Removed from Wishlist', message: this.inWishlist ? 'Product saved to your wishlist.' : 'Product removed from your wishlist.' })
      } catch {}
      finally { this.togglingWishlist = false }
    },
    async submitReview() {
      if (!this.newRating) { this.reviewError = 'Please select a rating.'; return }
      this.submittingReview = true; this.reviewError = ''
      try {
        await post(`/api/products/${this.product.id}/reviews`, { rating: this.newRating, comment: this.newComment })
        this.reviewSubmitted = true; this.fetchReviews()
      } catch (err) { this.reviewError = err.data?.message || 'Failed to submit review.' }
      finally { this.submittingReview = false }
    },
  },
}
</script>
