<template>
  <div>
    <!-- Header -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 px-4">
      <div class="max-w-7xl mx-auto animate-fade-in-down">
        <h1 class="text-4xl font-bold mb-2">Shopping Cart</h1>
        <p class="text-lg">Review your items before checkout</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-12">
      <!-- Loading -->
      <div v-if="loading" class="text-center py-16">
        <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
        <p class="text-gray-500">Loading your cart...</p>
      </div>

      <!-- Empty -->
      <div v-else-if="!items || items.length === 0" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-28 h-28 bg-gray-100 rounded-full mb-6">
          <i class="fas fa-shopping-cart text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h3>
        <p class="text-gray-500 mb-8">Looks like you haven't added anything to your cart yet.</p>
        <router-link
          to="/products"
          class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition"
        >
          Start Shopping
        </router-link>
      </div>

      <!-- Cart Content -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">
              {{ count }} {{ count === 1 ? 'Item' : 'Items' }}
            </h2>
            <button
              @click="clearCart"
              :disabled="clearing"
              class="text-red-600 hover:text-red-800 font-semibold text-sm transition disabled:opacity-50"
            >
              <i v-if="clearing" class="fas fa-spinner fa-spin mr-1"></i>
              Clear Cart
            </button>
          </div>

          <div
            v-for="(item, index) in items"
            :key="item.id"
            class="bg-white rounded-lg shadow-md p-6 flex flex-col sm:flex-row gap-4 animate-fade-in-up"
            :style="{ animationDelay: `${index * 80}ms` }"
          >
            <!-- Product Image -->
            <router-link :to="`/products/${item.product.id}`" class="shrink-0">
              <img
                :src="item.product.image_url || 'https://via.placeholder.com/120x120'"
                :alt="item.product.name"
                class="w-28 h-28 object-cover rounded-lg"
              />
            </router-link>

            <!-- Product Info -->
            <div class="flex-1 min-w-0">
              <router-link
                :to="`/products/${item.product.id}`"
                class="font-bold text-lg text-gray-900 hover:text-blue-600 transition block mb-1"
              >
                {{ item.product.name }}
              </router-link>
              <p v-if="item.product.category" class="text-sm text-gray-500 mb-3">
                {{ item.product.category.name }}
              </p>
              <p class="text-sm mb-3">
                <span v-if="item.product.stock > 0" class="text-green-600 font-medium">
                  In Stock
                </span>
                <span v-else class="text-red-600 font-medium">Out of Stock</span>
              </p>

              <!-- Quantity & Price -->
              <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center border border-gray-300 rounded-lg">
                  <button
                    @click="decrementQty(item)"
                    :disabled="item.quantity <= 1 || updatingId === item.id"
                    class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-lg transition disabled:opacity-50"
                  >
                    -
                  </button>
                  <span class="px-4 py-2 font-semibold min-w-[40px] text-center">
                    <i v-if="updatingId === item.id" class="fas fa-spinner fa-spin text-sm"></i>
                    <span v-else>{{ item.quantity }}</span>
                  </span>
                  <button
                    @click="incrementQty(item)"
                    :disabled="item.quantity >= item.product.stock || updatingId === item.id"
                    class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-lg transition disabled:opacity-50"
                  >
                    +
                  </button>
                </div>
                <div class="text-right">
                  <p class="text-lg font-bold text-blue-600">
                    ${{ (item.product.price * item.quantity).toFixed(2) }}
                  </p>
                  <p class="text-xs text-gray-500">${{ item.product.price?.toFixed(2) }} each</p>
                </div>
              </div>
            </div>

            <!-- Remove Button -->
            <div class="flex sm:flex-col justify-center items-center gap-2">
              <button
                @click="removeItem(item)"
                :disabled="removingId === item.id"
                class="text-gray-400 hover:text-red-600 transition disabled:opacity-50"
                title="Remove item"
              >
                <i v-if="removingId === item.id" class="fas fa-spinner fa-spin"></i>
                <i v-else class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>

          <!-- Continue Shopping -->
          <router-link
            to="/products"
            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition mt-4"
          >
            <i class="fas fa-arrow-left"></i> Continue Shopping
          </router-link>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-md p-6 sticky top-24 animate-scale-in">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h3>

            <div class="space-y-3 mb-6">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal ({{ count }} items)</span>
                <span>${{ subtotal?.toFixed(2) || '0.00' }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span class="text-green-600 font-medium">Free</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Tax (10%)</span>
                <span>${{ taxAmount }}</span>
              </div>
              <hr class="my-3" />
              <div class="flex justify-between text-lg font-bold text-gray-900">
                <span>Estimated Total</span>
                <span class="text-blue-600">${{ estimatedTotal }}</span>
              </div>
            </div>

            <router-link
              to="/checkout"
              class="block w-full bg-blue-600 text-white text-center font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition"
            >
              Proceed to Checkout
            </router-link>

            <div class="mt-4 text-center text-sm text-gray-500">
              <i class="fas fa-lock mr-1"></i> Secure checkout
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CartPage',
  data() {
    return {
      items: [],
      count: 0,
      subtotal: 0,
      loading: true,
      updatingId: null,
      removingId: null,
      clearing: false,
    }
  },
  computed: {
    taxAmount() {
      return (this.subtotal * 0.1).toFixed(2)
    },
    estimatedTotal() {
      return (this.subtotal + this.subtotal * 0.1).toFixed(2)
    },
  },
  async mounted() {
    await this.fetchCart()
  },
  methods: {
    async fetchCart() {
      this.loading = true
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/cart`, {
          headers: { Authorization: `Bearer ${token}` },
        })
        if (res.ok) {
          const data = await res.json()
          this.items = data.items || []
          this.count = data.count || 0
          this.subtotal = data.subtotal || 0
        }
      } catch {
        this.items = []
      } finally {
        this.loading = false
      }
    },
    async updateQty(item, newQty) {
      if (newQty < 1 || newQty > item.product.stock) return
      this.updatingId = item.id
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/cart/${item.id}`, {
          method: 'PUT',
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ quantity: newQty }),
        })
        if (res.ok) {
          const data = await res.json()
          this.items = data.items || []
          this.count = data.count || 0
          this.subtotal = data.subtotal || 0
        }
      } catch {
        // ignore
      } finally {
        this.updatingId = null
      }
    },
    incrementQty(item) {
      this.updateQty(item, item.quantity + 1)
    },
    decrementQty(item) {
      if (item.quantity > 1) {
        this.updateQty(item, item.quantity - 1)
      }
    },
    async removeItem(item) {
      if (this.removingId) return
      this.removingId = item.id
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/cart/${item.id}`, {
          method: 'DELETE',
          headers: { Authorization: `Bearer ${token}` },
        })
        if (res.ok) {
          const data = await res.json()
          this.items = data.items || []
          this.count = data.count || 0
          this.subtotal = data.subtotal || 0
        }
      } catch {
        // ignore
      } finally {
        this.removingId = null
      }
    },
    async clearCart() {
      if (!confirm('Are you sure you want to remove all items from your cart?')) return
      this.clearing = true
      try {
        const token = localStorage.getItem('token')
        await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/cart/clear`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
          },
        })
        this.items = []
        this.count = 0
        this.subtotal = 0
      } catch {
        // ignore
      } finally {
        this.clearing = false
      }
    },
  },
}
</script>
