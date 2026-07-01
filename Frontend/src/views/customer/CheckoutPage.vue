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

      <div v-else-if="!items || items.length === 0 && !orderPlaced" class="text-center py-20">
        <div class="inline-flex items-center justify-center w-28 h-28 bg-gray-100 rounded-full mb-6">
          <i class="fas fa-shopping-cart text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Nothing to checkout</h3>
        <p class="text-gray-500 mb-8">Your cart is empty.</p>
        <router-link to="/products" class="btn-primary text-base py-3 px-8">Browse Products</router-link>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
          <!-- Step indicator -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 animate-fade-in-up overflow-x-auto">
            <div class="flex items-center justify-between min-w-[400px] sm:min-w-0">
              <div v-for="(step, i) in steps" :key="i" class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold shrink-0"
                  :class="i < stepIndex ? 'bg-emerald-500 text-white' : i === stepIndex ? 'bg-blue-500 text-white ring-4 ring-blue-100' : 'bg-gray-100 text-gray-400'">
                  <i v-if="i < stepIndex" class="fas fa-check text-[10px]"></i>
                  <span v-else>{{ i + 1 }}</span>
                </div>
                <span class="text-xs sm:text-sm font-medium whitespace-nowrap" :class="i <= stepIndex ? 'text-gray-900' : 'text-gray-400'">{{ step }}</span>
                <i v-if="i < steps.length - 1" class="fas fa-chevron-right text-[10px] text-gray-300 mx-1 sm:mx-2"></i>
              </div>
            </div>
          </div>

          <!-- Shipping -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 animate-fade-in-left">
            <div class="flex items-center gap-3 mb-5">
              <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fas fa-location-dot text-blue-500"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900">Shipping Information</h3>
                <p class="text-xs text-gray-400">Enter your delivery details</p>
              </div>
            </div>
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
            <div class="flex items-center gap-3 mb-5">
              <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="fas fa-credit-card text-purple-500"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900">Payment Method</h3>
                <p class="text-xs text-gray-400">Choose how to pay</p>
              </div>
            </div>
            <div v-if="paymentMethods.length === 0" class="text-sm text-gray-500 py-4 text-center">Loading payment methods...</div>
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <label v-for="method in paymentMethods" :key="method.code"
                class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200"
                :class="paymentMethod === method.code ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'">
                <input type="radio" name="payment_method" :value="method.code" v-model="paymentMethod" class="sr-only" />
                <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shrink-0 overflow-hidden border border-gray-200 p-1">
                  <img v-if="method.qr_url" :src="method.qr_url" :alt="method.name" class="w-full h-full object-contain" />
                  <i v-else class="fas fa-university text-gray-300 text-2xl"></i>
                </div>
                <div class="flex-1">
                  <p class="font-semibold text-gray-900">{{ method.name }}</p>
                  <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                    <i class="fas fa-qrcode"></i> Scan QR to pay
                  </p>
                </div>
                <div v-if="paymentMethod === method.code" class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
              </label>
            </div>

            <transition name="slide">
              <div v-if="paymentMethod && selectedMethodPreview" class="mt-6 pt-6 border-t border-gray-100 space-y-4">
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-6 text-center">
                  <h4 class="font-bold text-gray-900 mb-1">{{ selectedMethodPreview.name }}</h4>
                  <p class="text-sm text-gray-500 mb-4">Scan the QR code below with your banking app</p>
                  <div class="inline-block bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
                    <img :src="selectedMethodPreview.qr_url || ''" alt="QR" class="w-48 h-48 object-contain mx-auto" />
                  </div>
                  <div class="mt-4 flex items-center justify-center gap-2 text-sm text-gray-500">
                    <i class="fas fa-mobile-screen-button"></i>
                    <span>Open banking app and scan</span>
                  </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                  <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                      <i class="fas fa-upload text-blue-500 text-sm"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm">Upload Payment Receipt</h4>
                  </div>
                  <p class="text-xs text-gray-500 mb-4">After paying, upload a screenshot or photo of the transaction receipt for admin verification.</p>

                  <div v-if="!proofUploaded">
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-5 text-center hover:border-blue-400 transition cursor-pointer bg-gray-50/50"
                      @click="$refs.proofInput?.click()">
                      <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-1"></i>
                      <p class="text-sm font-medium text-gray-600">Click to upload receipt</p>
                      <p class="text-xs text-gray-400 mt-1">JPG or PNG, max 5MB</p>
                      <input ref="proofInput" type="file" accept="image/jpeg,image/png" class="hidden" @change="handleProofFile" />
                    </div>
                    <div v-if="proofFile" class="mt-3 flex items-center gap-3 bg-blue-50 rounded-lg p-3 border border-blue-200">
                      <i class="fas fa-file-image text-blue-500 text-lg"></i>
                      <span class="text-sm font-medium text-gray-700 flex-1 truncate">{{ proofFile.name }}</span>
                      <span class="text-xs text-gray-400">({{ (proofFile.size / 1024).toFixed(1) }} KB)</span>
                      <button @click="proofFile = null; if($refs.proofInput) $refs.proofInput.value = ''" class="text-gray-400 hover:text-red-500">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                    <div v-if="proofError" class="mt-2 text-sm text-red-600 flex items-center gap-1">
                      <i class="fas fa-exclamation-circle"></i> {{ proofError }}
                    </div>
                  </div>
              </div>
            </div>
            </transition>
          </div>

          <!-- Order Items -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-5">
              <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                <i class="fas fa-bag-shopping text-amber-500"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900">Order Items ({{ items.length }})</h3>
                <p class="text-xs text-gray-400">Review your items</p>
              </div>
            </div>
            <div class="divide-y divide-gray-100">
              <div v-for="item in items" :key="item.id" class="py-3 flex gap-4 items-center">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 border border-gray-200 shrink-0">
                  <img :src="item.product.image_url || 'https://placehold.co/80x80/e2e8f0/64748b?text=Item'"
                    :alt="item.product.name" class="w-full h-full object-cover" />
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-gray-900 text-sm truncate">{{ item.product.name }}</p>
                  <p class="text-xs text-gray-500 mt-0.5">Qty: {{ item.quantity }} &times; ${{ Number(item.product.price).toFixed(2) }}</p>
                </div>
                <p class="font-bold text-gray-900">${{ (item.product.price * item.quantity).toFixed(2) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24 animate-scale-in">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                <i class="fas fa-receipt text-emerald-500"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900">Summary</h3>
                <p class="text-xs text-gray-400">Order overview</p>
              </div>
            </div>
            <div class="space-y-3 mb-6">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium text-gray-900">${{ Number(subtotal).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Shipping</span>
                <span class="text-emerald-600 font-medium flex items-center gap-1"><i class="fas fa-check-circle text-[10px]"></i> Free</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax (10%)</span>
                <span class="font-medium text-gray-900">${{ taxAmount }}</span>
              </div>
              <div class="border-t border-gray-100 pt-3 mt-3">
                <div class="flex justify-between text-lg font-bold">
                  <span class="text-gray-900">Total</span>
                  <span class="text-blue-500">${{ estimatedTotal }}</span>
                </div>
              </div>
            </div>

            <div v-if="checkoutErrors.length" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
              <ul class="space-y-1 list-disc list-inside">
                <li v-for="(err, idx) in checkoutErrors" :key="idx">{{ err }}</li>
              </ul>
            </div>
            <div v-if="checkoutError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-start gap-2">
              <i class="fas fa-exclamation-circle mt-0.5"></i>
              <span>{{ checkoutError }}</span>
            </div>

            <button @click="showReceipt = true" :disabled="items.length === 0 || !paymentMethod"
              class="w-full bg-blue-500 text-white font-bold py-3.5 rounded-xl hover:bg-blue-600 active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
              <i class="fas fa-file-invoice"></i>
              Review Receipt
            </button>

            <div class="mt-3 flex items-center justify-center gap-4 text-xs text-gray-400">
              <span class="flex items-center gap-1"><i class="fas fa-lock"></i> Secure</span>
              <router-link to="/cart" class="text-blue-500 hover:text-blue-600 hover:underline">Back to Cart</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Teleport to="body">
      <div>
        <transition name="modal">
          <div v-if="orderPlaced" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center animate-scale-in">
              <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-5">
                <i class="fas fa-check-circle text-4xl text-green-500"></i>
              </div>
              <h3 class="text-xl font-bold text-gray-900 mb-2">Order Placed!</h3>
              <p class="text-gray-600 mb-6">Order #{{ placedOrderId }} has been placed successfully!<br>Total: <strong>${{ placedTotal }}</strong></p>
              <div class="flex flex-col gap-3">
                <router-link to="/orders" @click="orderPlaced = false" class="w-full bg-blue-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-600 transition text-center">
                  View My Orders
                </router-link>
                <router-link to="/products" @click="orderPlaced = false" class="w-full bg-white text-blue-500 font-bold py-3 px-6 rounded-lg border-2 border-blue-500 hover:bg-blue-50 transition text-center">
                  Continue Shopping
                </router-link>
              </div>
            </div>
          </div>
        </transition>
      </div>
      <div>
        <transition name="modal">
          <div v-if="showReceipt" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" @click.self="showReceipt = false">
          <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto animate-scale-in">
            <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                  <i class="fas fa-file-invoice text-blue-500"></i>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-gray-900">Review Receipt</h3>
                  <p class="text-xs text-gray-400">Please verify your order before placing</p>
                </div>
              </div>
              <button @click="showReceipt = false" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                <i class="fas fa-times"></i>
              </button>
            </div>

            <div class="p-6 space-y-5">
              <div class="bg-gray-50 rounded-xl p-4">
                <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                  <i class="fas fa-box text-blue-500 text-xs"></i> Order Items ({{ items.length }})
                </h4>
                <div class="divide-y divide-gray-200">
                  <div v-for="item in items" :key="item.id" class="py-2.5 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 overflow-hidden shrink-0">
                      <img :src="item.product.image_url || 'https://placehold.co/40x40/e2e8f0/64748b?text=P'" class="w-full h-full object-cover" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900 truncate">{{ item.product.name }}</p>
                      <p class="text-xs text-gray-400">Qty: {{ item.quantity }} &times; ${{ Number(item.product.price).toFixed(2) }}</p>
                    </div>
                    <p class="text-sm font-bold text-gray-900">${{ (item.product.price * item.quantity).toFixed(2) }}</p>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                  <p class="text-xs text-gray-500 mb-1">Shipping To</p>
                  <p class="text-sm font-semibold text-gray-900">{{ shipping.name || 'N/A' }}</p>
                  <p class="text-xs text-gray-500">{{ shipping.address ? shipping.address + ', ' + shipping.city : 'N/A' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                  <p class="text-xs text-gray-500 mb-1">Payment</p>
                  <p class="text-sm font-semibold text-gray-900">{{ selectedMethodPreview?.name || 'N/A' }}</p>
                  <p class="text-xs text-gray-500">Pay via QR</p>
                </div>
              </div>

              <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Subtotal</span>
                  <span class="font-medium text-gray-900">${{ Number(subtotal).toFixed(2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Shipping</span>
                  <span class="text-emerald-600 font-medium">Free</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Tax (10%)</span>
                  <span class="font-medium text-gray-900">${{ taxAmount }}</span>
                </div>
                <div class="border-t border-gray-200 pt-2 mt-2">
                  <div class="flex justify-between text-lg font-bold">
                    <span class="text-gray-900">Total</span>
                    <span class="text-blue-500">${{ estimatedTotal }}</span>
                  </div>
                </div>
              </div>

              <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2">
                <i class="fas fa-info-circle text-amber-600 mt-0.5 text-sm"></i>
                <p class="text-xs text-amber-800">By placing this order, you agree to our terms of service. Your order will be processed once payment is verified.</p>
              </div>
            </div>

            <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4 flex gap-3 rounded-b-2xl">
              <button @click="showReceipt = false" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                Edit Order
              </button>
              <button @click="placeOrder" :disabled="placing"
                class="flex-1 px-4 py-2.5 bg-blue-500 rounded-xl text-sm font-semibold text-white hover:bg-blue-600 transition disabled:opacity-50 flex items-center justify-center gap-2">
                <i v-if="placing" class="fas fa-spinner fa-spin"></i>
                <i v-else class="fas fa-check-circle"></i>
                {{ placing ? 'Placing...' : 'Confirm & Place Order' }}
              </button>
            </div>
          </div>
        </div>
      </transition>
    </div>
    </Teleport>
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
      showReceipt: false,
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
        const u = d.user || d.data || {}
        if (!this.shipping.name && u.name) this.shipping.name = u.name
        if (!this.shipping.email && u.email) this.shipping.email = u.email
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
        this.showReceipt = false
        this.orderPlaced = true; this.placedOrderId = data.order?.id || ''; this.placedTotal = Number(data.order?.total || 0).toFixed(2)
        this.items = []; this.cartCount = 0; this.subtotal = 0; this.stepIndex = 3
      } catch (err) { this.checkoutError = err.data?.message || 'Failed to place order.'; this.showReceipt = false }
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

<style scoped>
.slide-enter-active { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
.slide-leave-active { transition: all 0.2s ease; }
.slide-enter-from { opacity: 0; transform: translateY(-8px); }
.slide-leave-to { opacity: 0; transform: translateY(8px); }
.modal-enter-active { transition: all 0.25s ease; }
.modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from { opacity: 0; }
.modal-leave-to { opacity: 0; }
</style>
