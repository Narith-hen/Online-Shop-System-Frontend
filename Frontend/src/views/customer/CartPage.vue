<template>
  <div>
    <section class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-12 px-4">
      <div class="max-w-7xl mx-auto animate-fade-in-down">
        <h1 class="text-4xl font-bold mb-2">Shopping Cart</h1>
        <p class="text-lg text-blue-200">Review your items before checkout</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-12">
      <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
          <div v-for="i in 3" :key="i" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex gap-4 animate-fade-in-up" :style="{ animationDelay: `${i * 80}ms` }">
            <div class="skeleton skeleton-image w-24 h-24 rounded-lg shrink-0"></div>
            <div class="flex-1 space-y-3">
              <div class="skeleton skeleton-text w-3/4"></div>
              <div class="skeleton skeleton-text-sm w-1/3"></div>
              <div class="flex items-center gap-4 pt-2">
                <div class="skeleton skeleton-button w-28"></div>
                <div class="skeleton skeleton-text-lg w-20"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="skeleton skeleton-text-lg w-1/2"></div>
            <div class="space-y-3">
              <div v-for="j in 3" :key="j" class="skeleton skeleton-text"></div>
            </div>
            <div class="skeleton skeleton-button w-full h-11"></div>
          </div>
        </div>
      </div>

      <div v-else-if="!items || items.length === 0" class="text-center py-20 animate-fade-in">
        <div class="inline-flex items-center justify-center w-28 h-28 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
          <i class="fas fa-cart-shopping text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h3>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">Looks like you haven't added anything yet. Browse our products and find something you love!</p>
        <router-link to="/products" class="btn-primary text-base py-3 px-8">
          <i class="fas fa-arrow-left mr-1"></i> Start Shopping
        </router-link>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
          <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-bold text-gray-900">{{ count }} {{ count === 1 ? 'Item' : 'Items' }}</h2>
            <button @click="showClearConfirm" :disabled="clearing"
              class="text-sm text-red-600 hover:text-red-700 font-semibold transition disabled:opacity-50 flex items-center gap-1.5">
              <i v-if="clearing" class="fas fa-spinner fa-spin"></i>
              <i v-else class="fas fa-trash-alt"></i> Clear Cart
            </button>
          </div>

          <div v-for="(item, index) in items" :key="item.id"
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-5 flex flex-col sm:flex-row gap-4 animate-fade-in-up"
            :style="{ animationDelay: `${index * 60}ms` }">
            <router-link :to="`/products/${item.product.id}`" class="shrink-0">
              <div class="w-24 h-24 rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                <img :src="item.product.image_url || 'https://placehold.co/120x120/e2e8f0/64748b?text=No+Image'"
                  :alt="item.product.name" class="w-full h-full object-cover" />
              </div>
            </router-link>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <div>
                  <router-link :to="`/products/${item.product.id}`"
                    class="font-semibold text-gray-900 hover:text-blue-500 transition block mb-0.5">
                    {{ item.product.name }}
                  </router-link>
                  <p v-if="item.product.category" class="text-xs text-gray-500 mb-2">{{ item.product.category.name }}</p>
                </div>
                <button @click="removeItem(item)" :disabled="removingId === item.id"
                  class="text-gray-300 hover:text-red-500 transition shrink-0 disabled:opacity-50 w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50" title="Remove">
                  <i v-if="removingId === item.id" class="fas fa-spinner fa-spin text-xs"></i>
                  <i v-else class="fas fa-xmark"></i>
                </button>
              </div>
              <div class="flex items-center justify-between flex-wrap gap-3 mt-3">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                  <button @click="decrementQty(item)" :disabled="item.quantity <= 1 || updatingId === item.id"
                    class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition disabled:opacity-40 text-lg">−</button>
                  <span class="w-10 h-9 flex items-center justify-center text-sm font-semibold border-x border-gray-300 bg-white">
                    <i v-if="updatingId === item.id" class="fas fa-spinner fa-spin text-xs"></i>
                    <span v-else>{{ item.quantity }}</span>
                  </span>
                  <button @click="incrementQty(item)" :disabled="item.quantity >= item.product.stock || updatingId === item.id"
                    class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition disabled:opacity-40 text-lg">+</button>
                </div>
                <div class="text-right">
                  <p class="text-lg font-bold text-blue-500">${{ (item.product.price * item.quantity).toFixed(2) }}</p>
                  <p class="text-xs text-gray-400">${{ Number(item.product.price).toFixed(2) }} each</p>
                </div>
              </div>
            </div>
          </div>

          <router-link to="/products" class="inline-flex items-center gap-2 text-sm text-blue-500 hover:text-blue-600 font-medium transition mt-2">
            <i class="fas fa-arrow-left"></i> Continue Shopping
          </router-link>
        </div>

        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24 animate-scale-in">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h3>
            <div class="space-y-3 mb-6">
              <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal ({{ count }} items)</span>
                <span class="font-medium text-gray-900">${{ Number(subtotal).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-sm text-gray-600">
                <span>Shipping</span>
                <span class="text-emerald-600 font-medium">Free</span>
              </div>
              <div class="flex justify-between text-sm text-gray-600">
                <span>Tax (10%)</span>
                <span class="font-medium text-gray-900">${{ taxAmount }}</span>
              </div>
              <hr class="border-gray-100" />
              <div class="flex justify-between text-lg font-bold text-gray-900">
                <span>Total</span>
                <span class="text-blue-500">${{ estimatedTotal }}</span>
              </div>
            </div>
            <router-link to="/checkout" class="w-full btn-primary py-3 text-base justify-center">
              <i class="fas fa-arrow-right mr-1"></i> Proceed to Checkout
            </router-link>
            <p class="mt-3 text-center text-xs text-gray-400">
              <i class="fas fa-lock mr-1"></i> Secure checkout
            </p>
          </div>
        </div>
      </div>
    </div>

    <Teleport to="body">
      <transition name="modal">
        <div v-if="confirmVisible" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" @click.self="confirmVisible = false">
          <div class="absolute inset-0 bg-black/40"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center animate-scale-in">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
              <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Clear Cart?</h3>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to remove all {{ count }} items from your cart?</p>
            <div class="flex gap-3">
              <button @click="confirmVisible = false" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                Cancel
              </button>
              <button @click="clearCart" class="flex-1 px-4 py-2.5 bg-red-600 rounded-lg text-sm font-semibold text-white hover:bg-red-700 transition">
                Clear All
              </button>
            </div>
          </div>
        </div>
      </transition>
    </Teleport>

    <Toast ref="toastRef" />
  </div>
</template>

<script>
import Toast from '@/components/Toast.vue'
import { get, put, del, post } from '@/services/api'

export default {
  name: 'CartPage',
  components: { Toast },
  data() {
    return {
      loading: true, clearing: false, removingId: null, updatingId: null,
      confirmVisible: false,
      items: [], count: 0, subtotal: 0,
    }
  },
  computed: {
    taxAmount() { return (this.subtotal * 0.1).toFixed(2) },
    estimatedTotal() { return (this.subtotal + this.subtotal * 0.1).toFixed(2) },
  },
  async mounted() { await this.fetchCart() },
  methods: {
    async fetchCart() {
      this.loading = true
      try {
        const data = await get('/api/cart')
        this.items = data.items || []; this.count = data.count || 0; this.subtotal = data.subtotal || 0
      } catch { this.items = [] }
      finally { this.loading = false }
    },
    showClearConfirm() { this.confirmVisible = true },
    async clearCart() {
      this.confirmVisible = false
      this.clearing = true
      try {
        await post('/api/cart/clear')
        this.items = []; this.count = 0; this.subtotal = 0
        this.$refs.toastRef?.show({ type: 'success', title: 'Cart Cleared', message: 'All items have been removed from your cart.' })
      } catch { this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: 'Failed to clear cart.' }) }
      finally { this.clearing = false }
    },
    async updateQty(item, newQty) {
      if (newQty < 1 || newQty > item.product.stock) return
      this.updatingId = item.id
      try {
        const data = await put(`/api/cart/${item.id}`, { quantity: newQty })
        this.items = data.items || []; this.count = data.count || 0; this.subtotal = data.subtotal || 0
      } catch { this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: 'Failed to update quantity.' }) }
      finally { this.updatingId = null }
    },
    incrementQty(item) { this.updateQty(item, item.quantity + 1) },
    decrementQty(item) { if (item.quantity > 1) this.updateQty(item, item.quantity - 1) },
    async removeItem(item) {
      if (this.removingId) return
      this.removingId = item.id
      try {
        const data = await del(`/api/cart/${item.id}`)
        this.items = data.items || []; this.count = data.count || 0; this.subtotal = data.subtotal || 0
        this.$refs.toastRef?.show({ type: 'success', title: 'Removed', message: 'Item has been removed from your cart.' })
      } catch { this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: 'Failed to remove item.' }) }
      finally { this.removingId = null }
    },
  },
}
</script>

<style scoped>
.modal-enter-active { transition: all 0.25s ease; }
.modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from { opacity: 0; }
.modal-leave-to { opacity: 0; }
</style>
