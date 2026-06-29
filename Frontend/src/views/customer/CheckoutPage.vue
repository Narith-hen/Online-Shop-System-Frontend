<template>
  <div>
    <section class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-12 px-4">
      <div class="max-w-7xl mx-auto animate-fade-in-down">
        <h1 class="text-4xl font-bold mb-2">Checkout</h1>
        <p class="text-lg text-blue-100">Complete your purchase</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-12">
      <div v-if="loading" class="text-center py-16">
        <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
        <p class="text-gray-500">Loading your order details...</p>
      </div>

      <div v-else-if="!items || items.length === 0" class="text-center py-20">
        <div class="inline-flex items-center justify-center w-28 h-28 bg-gray-100 rounded-full mb-6">
          <i class="fas fa-shopping-cart text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Nothing to checkout</h3>
        <p class="text-gray-500 mb-8">Your cart is empty.</p>
        <router-link to="/products" class="btn-primary text-base py-3 px-8">Browse Products</router-link>
      </div>

      <!-- Success -->
      <div v-else-if="orderPlaced" class="max-w-2xl mx-auto animate-scale-in">
        <div class="text-center mb-8">
          <div class="inline-flex items-center justify-center w-28 h-28 bg-green-100 rounded-full mb-6">
            <i class="fas fa-check-circle text-5xl text-green-500"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Order Placed!</h3>
          <p class="text-gray-600">Order #{{ placedOrderId }} &mdash; <strong>${{ placedTotal }}</strong></p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Complete Your Payment</h3>
          <p class="text-gray-600 mb-6">Scan the QR code with your mobile banking app to pay.</p>
          <div v-if="selectedMethod" class="text-center">
            <div class="inline-block bg-gray-50 rounded-xl p-4 mb-4 border border-gray-200">
              <img :src="selectedMethod.qr_url || ''" :alt="selectedMethod.name"
                class="w-64 h-64 object-contain mx-auto rounded" />
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ selectedMethod.name }}</h4>
            <p class="text-sm text-gray-500 mb-4">{{ selectedMethod.instructions }}</p>
          </div>
          <hr class="my-6" />
          <div v-if="!proofUploaded">
            <h4 class="font-bold text-gray-900 mb-2">Upload Payment Screenshot</h4>
            <p class="text-sm text-gray-500 mb-3">After paying, upload a screenshot of the transaction.</p>
            <input ref="proofInput" type="file" accept="image/jpeg,image/png"
              class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-200 file:text-blue-500 hover:file:bg-blue-300" @change="handleProofFile" />
            <div v-if="proofError" class="mt-2 text-sm text-red-600">{{ proofError }}</div>
            <button v-if="proofFile" @click="uploadProof" :disabled="uploadingProof"
              class="mt-4 w-full bg-emerald-600 text-white font-bold py-2.5 rounded-lg hover:bg-emerald-700 transition disabled:opacity-50">
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
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <router-link to="/orders" class="btn-primary py-3 px-8">View My Orders</router-link>
          <router-link to="/products" class="btn-secondary py-3 px-8">Continue Shopping</router-link>
        </div>
      </div>

      <!-- Checkout Form -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
          <!-- Step indicator -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 animate-fade-in-up">
            <div class="flex items-center justify-between">
              <div v-for="(step, i) in steps" :key="i" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                  :class="i <= stepIndex ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-400'">
                  <i v-if="i < stepIndex" class="fas fa-check text-xs"></i>
                  <span v-else>{{ i + 1 }}</span>
                </div>
                <span class="text-sm font-medium" :class="i <= stepIndex ? 'text-gray-900' : 'text-gray-400'">{{ step }}</span>
                <i v-if="i < steps.length - 1" class="fas fa-chevron-right text-xs text-gray-300 mx-2"></i>
              </div>
            </div>
          </div>

          <!-- Shipping -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 animate-fade-in-left">
            <h3 class="text-lg font-bold text-gray-900 mb-5">Shipping Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input v-model="shipping.name" type="text" class="form-input" placeholder="John Doe" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input v-model="shipping.email" type="email" class="form-input" placeholder="john@example.com" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                <input v-model="shipping.phone" type="tel" class="form-input" placeholder="+1 (555) 123-4567" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">City</label>
                <input v-model="shipping.city" type="text" class="form-input" placeholder="New York" />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                <input v-model="shipping.address" type="text" class="form-input" placeholder="123 Main Street, Apt 4B" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">ZIP Code</label>
                <input v-model="shipping.zip" type="text" class="form-input" placeholder="10001" />
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 animate-fade-in-up">
            <h3 class="text-lg font-bold text-gray-900 mb-5">Payment Method</h3>
            <div v-if="paymentMethods.length === 0" class="text-sm text-gray-500">Loading payment methods...</div>
            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <label v-for="method in paymentMethods" :key="method.code"
                class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition"
                :class="paymentMethod === method.code ? 'border-blue-500 bg-blue-100' : 'border-gray-200 hover:border-gray-300'">
                <input type="radio" name="payment_method" :value="method.code" v-model="paymentMethod" class="sr-only" />
                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden border border-gray-200">
                  <img v-if="method.qr_url" :src="method.qr_url" :alt="method.name" class="w-full h-full object-contain" />
                  <i v-else class="fas fa-university text-gray-400 text-2xl"></i>
                </div>
                <div>
                  <p class="font-semibold text-gray-900">{{ method.name }}</p>
                  <p class="text-xs text-gray-500">Scan QR to pay</p>
                </div>
              </label>
            </div>
            <div v-if="paymentMethod && !orderPlaced" class="mt-6 text-center border-t border-gray-100 pt-6">
              <h4 class="font-bold text-gray-900 mb-3">Scan to Pay</h4>
              <div class="inline-block bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200">
                <img :src="selectedMethodPreview?.qr_url || ''" alt="QR" class="w-56 h-56 object-contain mx-auto rounded" />
              </div>
              <p class="text-sm text-gray-600">Open your banking app and scan this QR code</p>
            </div>
          </div>

          <!-- Order Items -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 animate-fade-in-up">
            <h3 class="text-lg font-bold text-gray-900 mb-5">Order Items ({{ items.length }})</h3>
            <div class="divide-y divide-gray-100">
              <div v-for="item in items" :key="item.id" class="py-3 flex gap-4 items-center">
                <img :src="item.product.image_url || 'https://via.placeholder.com/80x80'"
                  :alt="item.product.name" class="w-16 h-16 object-cover rounded-lg border border-gray-200" />
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-gray-900 text-sm">{{ item.product.name }}</p>
                  <p class="text-xs text-gray-500">Qty: {{ item.quantity }}</p>
                </div>
                <p class="font-semibold text-gray-900">${{ (item.product.price * item.quantity).toFixed(2) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24 animate-scale-in">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Payment Summary</h3>
            <div class="space-y-3 mb-6">
              <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal ({{ cartCount }} items)</span>
                <span class="font-medium text-gray-900">${{ subtotal?.toFixed(2) || '0.00' }}</span>
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
            <div v-if="checkoutErrors.length" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
              <ul class="space-y-1"><li v-for="(err, idx) in checkoutErrors" :key="idx">{{ err }}</li></ul>
            </div>
            <div v-if="checkoutError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ checkoutError }}</div>
            <button @click="placeOrder" :disabled="placing || items.length === 0"
              class="w-full btn-primary py-3 text-base justify-center">
              <i v-if="placing" class="fas fa-spinner fa-spin mr-2"></i>
              {{ placing ? 'Processing...' : 'Place Order' }}
            </button>
            <router-link to="/cart" class="w-full mt-2 btn-secondary py-3 justify-center">Back to Cart</router-link>
            <p class="mt-4 text-center text-xs text-gray-400">By placing your order, you agree to our Terms of Service.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Toast from '@/components/Toast.vue'
import { get, post } from '@/services/api'

export default {
  name: 'CheckoutPage',
  components: { Toast },
  data() {
    return {
      items: [], cartCount: 0, subtotal: 0, loading: true, placing: false,
      checkoutError: '', checkoutErrors: [], orderPlaced: false, placedOrderId: null, placedTotal: '0.00',
      paymentMethods: [], paymentMethod: '', selectedMethod: null,
      proofFile: null, proofError: '', uploadingProof: false, proofUploaded: false,
      stepIndex: 0,
      steps: ['Shipping', 'Payment', 'Confirmation'],
      shipping: { name: '', email: '', phone: '', address: '', city: '', zip: '' },
    }
  },
  computed: {
    taxAmount() { return (this.subtotal * 0.1).toFixed(2) },
    estimatedTotal() { return (this.subtotal + this.subtotal * 0.1).toFixed(2) },
    selectedMethodPreview() { return this.paymentMethods.find(m => m.code === this.paymentMethod) || null },
  },
  async mounted() {
    await Promise.all([this.fetchCart(), this.fetchPaymentMethods()])
    this.populateShippingFromProfile()
  },
  methods: {
    async fetchCart() {
      this.loading = true
      try {
        const d = await get('/api/cart')
        this.items = d.items || []; this.cartCount = d.count || 0; this.subtotal = d.subtotal || 0
      } catch { this.items = [] }
      finally { this.loading = false }
    },
    async populateShippingFromProfile() {
      try {
        const d = await get('/api/profile')
        const u = d.user || d.data || {}; if (!this.shipping.name && u.name) this.shipping.name = u.name; if (!this.shipping.email && u.email) this.shipping.email = u.email
      } catch {}
    },
    async fetchPaymentMethods() {
      try { const d = await get('/api/payment-methods'); this.paymentMethods = d.data || [] }
      catch { this.paymentMethods = [] }
    },
    async placeOrder() {
      if (!this.paymentMethod) { this.checkoutError = 'Please select a payment method.'; return }
      this.checkoutError = ''; this.checkoutErrors = []; this.placing = true
      try {
        const data = await post('/api/checkout', {
          payment_method: this.paymentMethod,
          shipping_name: this.shipping.name, shipping_phone: this.shipping.phone,
          shipping_address: this.shipping.address, shipping_city: this.shipping.city, shipping_zip: this.shipping.zip,
        })
        this.orderPlaced = true; this.placedOrderId = data.order?.id || ''; this.placedTotal = Number(data.order?.total || 0).toFixed(2)
        this.selectedMethod = this.paymentMethods.find(m => m.code === this.paymentMethod) || null
        this.items = []; this.cartCount = 0; this.subtotal = 0; this.stepIndex = 3
      } catch (err) { this.checkoutError = err.data?.message || 'Failed to place order.' }
      finally { this.placing = false }
    },
    handleProofFile(e) {
      const file = e.target.files[0]; this.proofError = ''; this.proofFile = null
      if (!file) return
      if (!['image/jpeg', 'image/png'].includes(file.type)) { this.proofError = 'Only JPG and PNG files are allowed.'; return }
      if (file.size > 5 * 1024 * 1024) { this.proofError = 'File must be less than 5MB.'; return }
      this.proofFile = file
    },
    async uploadProof() {
      if (!this.proofFile) return; this.uploadingProof = true; this.proofError = ''
      try {
        const fd = new FormData(); fd.append('payment_proof', this.proofFile)
        await post(`/api/orders/${this.placedOrderId}/payment-proof`, fd)
        this.proofUploaded = true; this.proofFile = null; if (this.$refs.proofInput) this.$refs.proofInput.value = ''
      } catch { this.proofError = 'Upload failed.' }
      finally { this.uploadingProof = false }
    },
  },
}
</script>
