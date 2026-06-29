<template>
  <div>
    <!-- Header -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 px-4">
      <div class="max-w-7xl mx-auto animate-fade-in-down">
        <h1 class="text-4xl font-bold mb-2">Checkout</h1>
        <p class="text-lg">Complete your purchase</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-12">
      <!-- Loading -->
      <div v-if="loading" class="text-center py-16">
        <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
        <p class="text-gray-500">Loading your order details...</p>
      </div>

      <!-- Empty Cart -->
      <div v-else-if="!items || items.length === 0" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-28 h-28 bg-gray-100 rounded-full mb-6">
          <i class="fas fa-shopping-cart text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Nothing to checkout</h3>
        <p class="text-gray-500 mb-8">Your cart is empty. Add items before checking out.</p>
        <router-link
          to="/products"
          class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition"
        >
          Browse Products
        </router-link>
      </div>

      <!-- Success / Payment -->
      <div v-else-if="orderPlaced" class="max-w-2xl mx-auto animate-scale-in">
        <div class="text-center mb-8">
          <div class="inline-flex items-center justify-center w-28 h-28 bg-green-100 rounded-full mb-6">
            <i class="fas fa-check-circle text-5xl text-green-500"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Order Placed!</h3>
          <p class="text-gray-600">Order #{{ placedOrderId }} &mdash; Total <strong>${{ placedTotal }}</strong></p>
        </div>

        <!-- Payment Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Complete Your Payment</h3>
          <p class="text-gray-600 mb-6">Scan the QR code below with your mobile banking app to pay.</p>

          <div v-if="selectedMethod" class="text-center">
            <div class="inline-block bg-gray-50 rounded-lg p-4 mb-4">
              <img
                :src="selectedMethod.qr_url || ''"
                :alt="selectedMethod.name"
                class="w-64 h-64 object-contain mx-auto rounded"
              />
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ selectedMethod.name }}</h4>
            <p class="text-sm text-gray-500 mb-4">{{ selectedMethod.instructions }}</p>
          </div>

          <hr class="my-6" />

          <!-- Upload Proof -->
          <div v-if="!proofUploaded">
            <h4 class="font-bold text-gray-900 mb-2">Upload Payment Screenshot</h4>
            <p class="text-sm text-gray-500 mb-3">After paying, upload a screenshot of the transaction.</p>
            <input
              ref="proofInput"
              type="file"
              accept="image/jpeg,image/png"
              class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
              @change="handleProofFile"
            />
            <div v-if="proofError" class="mt-2 text-sm text-red-600">{{ proofError }}</div>
            <button
              v-if="proofFile"
              @click="uploadProof"
              :disabled="uploadingProof"
              class="mt-4 w-full bg-green-600 text-white font-bold py-2 rounded-lg hover:bg-green-700 transition disabled:opacity-50"
            >
              <i v-if="uploadingProof" class="fas fa-spinner fa-spin mr-2"></i>
              {{ uploadingProof ? 'Uploading...' : 'Submit Payment Proof' }}
            </button>
          </div>
          <div v-else class="text-center py-4">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-3">
              <i class="fas fa-check text-2xl text-green-500"></i>
            </div>
            <p class="font-semibold text-green-700">Payment proof submitted! Awaiting verification.</p>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            to="/orders"
            class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition text-center"
          >
            View My Orders
          </router-link>
          <router-link
            to="/products"
            class="inline-block border border-blue-600 text-blue-600 font-bold py-3 px-8 rounded-lg hover:bg-blue-50 transition text-center"
          >
            Continue Shopping
          </router-link>
        </div>
      </div>

      <!-- Checkout Form -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items & Shipping -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Shipping Information -->
          <div class="bg-white rounded-lg shadow-md p-6 animate-fade-in-left">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Shipping Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input
                  v-model="shipping.name"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                  placeholder="John Doe"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input
                  v-model="shipping.email"
                  type="email"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                  placeholder="john@example.com"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                <input
                  v-model="shipping.phone"
                  type="tel"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                  placeholder="+1 (555) 123-4567"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">City</label>
                <input
                  v-model="shipping.city"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                  placeholder="New York"
                />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                <input
                  v-model="shipping.address"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                  placeholder="123 Main Street, Apt 4B"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">ZIP Code</label>
                <input
                  v-model="shipping.zip"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                  placeholder="10001"
                />
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="bg-white rounded-lg shadow-md p-6 animate-fade-in-up">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Payment Method</h3>
            <div v-if="paymentMethods.length === 0" class="text-sm text-gray-500">Loading payment methods...</div>
            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <label
                v-for="method in paymentMethods"
                :key="method.code"
                class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer transition"
                :class="paymentMethod === method.code ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-300'"
              >
                <input
                  type="radio"
                  name="payment_method"
                  :value="method.code"
                  v-model="paymentMethod"
                  class="sr-only"
                />
                <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center shrink-0 overflow-hidden">
                  <img
                    v-if="method.qr_url"
                    :src="method.qr_url"
                    :alt="method.name"
                    class="w-full h-full object-contain"
                  />
                  <i v-else class="fas fa-university text-gray-400 text-2xl"></i>
                </div>
                <div>
                  <p class="font-bold text-gray-900">{{ method.name }}</p>
                  <p class="text-xs text-gray-500">Scan QR to pay</p>
                </div>
              </label>
            </div>
            <!-- QR Code Preview -->
            <div v-if="paymentMethod && !orderPlaced" class="mt-6 text-center border-t border-gray-100 pt-6">
              <h4 class="font-bold text-gray-900 mb-3">Scan to Pay with ABA KHQR</h4>
              <div class="inline-block bg-gray-50 rounded-lg p-4 mb-3">
                <img
                  :src="selectedMethodPreview?.qr_url || ''"
                  alt="ABA KHQR"
                  class="w-64 h-64 object-contain mx-auto rounded"
                />
              </div>
              <p class="text-sm text-gray-600 mb-1">Open ABA Mobile app and scan this QR code</p>
              <p class="text-sm text-gray-500">You will upload the payment screenshot after placing your order</p>
            </div>
          </div>

          <!-- Order Items -->
          <div class="bg-white rounded-lg shadow-md p-6 animate-fade-in-up">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Order Items ({{ items.length }})</h3>
            <div class="divide-y">
              <div
                v-for="item in items"
                :key="item.id"
                class="py-4 flex gap-4 items-center"
              >
                <img
                  :src="item.product.image_url || 'https://via.placeholder.com/80x80'"
                  :alt="item.product.name"
                  class="w-20 h-20 object-cover rounded-lg"
                />
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-gray-900">{{ item.product.name }}</p>
                  <p class="text-sm text-gray-500">Qty: {{ item.quantity }}</p>
                </div>
                <p class="font-semibold text-gray-900">
                  ${{ (item.product.price * item.quantity).toFixed(2) }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-md p-6 sticky top-24 animate-scale-in">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Payment Summary</h3>

            <div class="space-y-3 mb-6">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal ({{ cartCount }} items)</span>
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
              <div class="flex justify-between text-xl font-bold text-gray-900">
                <span>Total</span>
                <span class="text-blue-600">${{ estimatedTotal }}</span>
              </div>
            </div>

            <!-- Error Messages -->
            <div v-if="checkoutErrors.length" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
              <ul class="text-sm text-red-700 space-y-1">
                <li v-for="(err, idx) in checkoutErrors" :key="idx">
                  {{ err }}
                </li>
              </ul>
            </div>

            <div v-if="checkoutError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
              {{ checkoutError }}
            </div>

            <button
              @click="placeOrder"
              :disabled="placing || items.length === 0"
              class="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i v-if="placing" class="fas fa-spinner fa-spin mr-2"></i>
              {{ placing ? 'Processing...' : 'Place Order' }}
            </button>

            <button
              @click="$router.push('/cart')"
              class="w-full mt-3 border border-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg hover:bg-gray-50 transition"
            >
              Back to Cart
            </button>

            <div class="mt-4 text-center text-xs text-gray-400">
              By placing your order, you agree to our Terms of Service and Privacy Policy.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CheckoutPage',
  data() {
    return {
      items: [],
      cartCount: 0,
      subtotal: 0,
      loading: true,
      placing: false,
      checkoutError: '',
      checkoutErrors: [],
      orderPlaced: false,
      placedOrderId: null,
      placedTotal: '0.00',
      paymentMethods: [],
      paymentMethod: '',
      selectedMethod: null,
      proofFile: null,
      proofError: '',
      uploadingProof: false,
      proofUploaded: false,
      shipping: {
        name: '',
        email: '',
        phone: '',
        address: '',
        city: '',
        zip: '',
      },
    }
  },
  computed: {
    taxAmount() {
      return (this.subtotal * 0.1).toFixed(2)
    },
    estimatedTotal() {
      return (this.subtotal + this.subtotal * 0.1).toFixed(2)
    },
    selectedMethodPreview() {
      return this.paymentMethods.find(m => m.code === this.paymentMethod) || null
    },
  },
  async mounted() {
    await Promise.all([this.fetchCart(), this.fetchPaymentMethods()])
    this.populateShippingFromProfile()
  },
  methods: {
    async fetchCart() {
      this.loading = true
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/cart`, {
          headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
        })
        if (res.ok) {
          const data = await res.json()
          this.items = data.items || []
          this.cartCount = data.count || 0
          this.subtotal = data.subtotal || 0
        }
      } catch {
        this.items = []
      } finally {
        this.loading = false
      }
    },
    async populateShippingFromProfile() {
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/profile`, {
          headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
        })
        if (res.ok) {
          const data = await res.json()
          const user = data.user || data.data || {}
          if (!this.shipping.name && user.name) this.shipping.name = user.name
          if (!this.shipping.email && user.email) this.shipping.email = user.email
        }
      } catch {
        // ignore
      }
    },
    async fetchPaymentMethods() {
      try {
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/payment-methods`)
        if (res.ok) {
          const data = await res.json()
          this.paymentMethods = data.data || []
        }
      } catch {
        this.paymentMethods = []
      }
    },
    async placeOrder() {
      if (!this.paymentMethod) {
        this.checkoutError = 'Please select a payment method.'
        return
      }
      this.checkoutError = ''
      this.checkoutErrors = []
      this.placing = true
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/checkout`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          body: JSON.stringify({
            payment_method: this.paymentMethod,
            shipping_name: this.shipping.name,
            shipping_phone: this.shipping.phone,
            shipping_address: this.shipping.address,
            shipping_city: this.shipping.city,
            shipping_zip: this.shipping.zip,
          }),
        })
        const data = await res.json()
        if (res.ok) {
          this.orderPlaced = true
          this.placedOrderId = data.order?.id || ''
          this.placedTotal = data.order?.total?.toFixed(2) || '0.00'
          this.selectedMethod = this.paymentMethods.find(m => m.code === this.paymentMethod) || null
          this.items = []
          this.cartCount = 0
          this.subtotal = 0
        } else {
          this.checkoutErrors = data.errors || []
          this.checkoutError = data.message || 'Failed to place order. Please try again.'
        }
      } catch {
        this.checkoutError = 'Failed to place order. Please try again.'
      } finally {
        this.placing = false
      }
    },
    handleProofFile(e) {
      const file = e.target.files[0]
      this.proofError = ''
      this.proofFile = null
      if (!file) return
      if (!['image/jpeg', 'image/png'].includes(file.type)) {
        this.proofError = 'Only JPG and PNG files are allowed.'
        return
      }
      if (file.size > 5 * 1024 * 1024) {
        this.proofError = 'File must be less than 5MB.'
        return
      }
      this.proofFile = file
    },
    async uploadProof() {
      if (!this.proofFile) return
      this.uploadingProof = true
      this.proofError = ''
      try {
        const token = localStorage.getItem('token')
        const formData = new FormData()
        formData.append('payment_proof', this.proofFile)
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/orders/${this.placedOrderId}/payment-proof`, {
          method: 'POST',
          headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
          body: formData,
        })
        if (res.ok) {
          this.proofUploaded = true
          this.proofFile = null
          if (this.$refs.proofInput) this.$refs.proofInput.value = ''
        } else {
          const data = await res.json()
          this.proofError = data.message || 'Upload failed. Please try again.'
        }
      } catch {
        this.proofError = 'Upload failed. Please try again.'
      } finally {
        this.uploadingProof = false
      }
    },
  },
}
</script>
