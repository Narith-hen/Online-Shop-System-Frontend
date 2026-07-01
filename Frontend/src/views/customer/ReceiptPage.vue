<template>
  <div class="max-w-3xl mx-auto px-4 py-8">
    <div v-if="loading" class="text-center py-16">
      <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
      <p class="text-gray-500">Loading receipt...</p>
    </div>

    <div v-else-if="error" class="text-center py-16">
      <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-6">
        <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
      </div>
      <h3 class="text-xl font-bold text-gray-900 mb-2">Receipt Not Found</h3>
      <p class="text-gray-500 mb-6">{{ error }}</p>
      <router-link to="/orders" class="inline-block bg-blue-500 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-600 transition">Back to Orders</router-link>
    </div>

    <div v-else-if="order" class="space-y-6">
      <!-- Receipt Card -->
      <div ref="receiptContent" id="receipt" class="bg-white rounded-lg shadow-md p-6 sm:p-8">
        <!-- Header -->
        <div class="border-b border-gray-200 pb-6 mb-6 text-center">
          <h1 class="text-3xl font-bold text-gray-900">ONLINE SHOP</h1>
          <p class="text-gray-500 text-sm mt-1">123 Shop Street, Phnom Penh, Cambodia</p>
          <p class="text-gray-500 text-sm">Phone: +855 12 345 678 | Email: support@onlineshop.com</p>
          <hr class="my-4" />
          <h2 class="text-xl font-bold text-gray-900">TAX INVOICE / RECEIPT</h2>
        </div>

        <!-- Order Info -->
        <div class="border-b border-gray-200 pb-6 mb-6 flex flex-col sm:flex-row justify-between items-start gap-4">
          <div>
            <p class="text-sm text-gray-500">Receipt #</p>
            <p class="font-bold text-gray-900">INV-{{ String(order.id).padStart(6, '0') }}</p>
            <p class="text-sm text-gray-500 mt-2">Order ID</p>
            <p class="font-semibold text-gray-900">#{{ order.id }}</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-500">Date</p>
            <p class="font-semibold text-gray-900">{{ formatDate(order.created_at) }}</p>
          </div>
        </div>

        <!-- Customer Info -->
        <div class="border-b border-gray-200 pb-6 mb-6">
          <h3 class="font-bold text-gray-900 mb-2 uppercase text-sm tracking-wide">Bill To</h3>
          <p class="text-sm text-gray-600">
            <span class="font-semibold">{{ order.customer_name || order.user?.name || 'N/A' }}</span>
          </p>
          <p class="text-sm text-gray-600">{{ order.user?.email || 'N/A' }}</p>
          <p class="text-sm text-gray-600" v-if="order.user?.phone">{{ order.user.phone }}</p>
          <div v-if="order.shipping_address" class="mt-2">
            <p class="text-sm text-gray-500 font-semibold">Shipping Address</p>
            <p class="text-sm text-gray-600">{{ order.shipping_name || order.customer_name }}</p>
            <p class="text-sm text-gray-600">{{ order.shipping_address }}</p>
            <p class="text-sm text-gray-600" v-if="order.shipping_city">{{ order.shipping_city }}{{ order.shipping_zip ? ', ' + order.shipping_zip : '' }}</p>
            <p class="text-sm text-gray-600" v-if="order.shipping_phone">Phone: {{ order.shipping_phone }}</p>
          </div>
          <p class="text-sm text-gray-600 mt-1" v-if="order.payment_method">
            Payment: <span class="font-semibold capitalize">{{ order.payment_method }}</span>
            <span class="inline-block ml-1 text-xs font-semibold px-2 py-0.5 rounded" :class="paymentStatusClass(order.payment_status)">
              {{ capitalize(order.payment_status || 'unpaid') }}
            </span>
          </p>
        </div>

        <!-- Items -->
        <div class="border-b border-gray-200 pb-6 mb-6">
          <h3 class="font-bold text-gray-900 mb-3 uppercase text-sm tracking-wide">Items</h3>
          <div class="overflow-x-auto -mx-2 px-2">
          <table class="w-full text-sm min-w-[400px]">
            <thead>
              <tr class="text-gray-500 border-b border-gray-100">
                <th class="text-left pb-2 font-semibold">Product</th>
                <th class="text-center pb-2 font-semibold">Qty</th>
                <th class="text-right pb-2 font-semibold">Price</th>
                <th class="text-right pb-2 font-semibold">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in order.items" :key="item.id" class="border-b border-gray-50">
                <td class="py-3 text-gray-900">{{ item.product?.name || 'Product' }}</td>
                <td class="py-3 text-center text-gray-600">{{ item.quantity }}</td>
                <td class="py-3 text-right text-gray-600">${{ item.price?.toFixed(2) }}</td>
                <td class="py-3 text-right font-semibold text-gray-900">${{ (item.quantity * item.price).toFixed(2) }}</td>
              </tr>
            </tbody>
          </table>
          </div>
        </div>

        <!-- Summary -->
        <div class="flex justify-end mb-6">
          <div class="w-64 space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
              <span>Subtotal</span>
              <span>${{ subtotal.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Tax (10%)</span>
              <span>${{ tax.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Shipping</span>
              <span class="text-green-600 font-medium">Free</span>
            </div>
            <hr class="my-2" />
            <div class="flex justify-between text-lg font-bold text-gray-900">
              <span>Total</span>
              <span class="text-blue-500">${{ order.total?.toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 pt-6 text-center text-xs text-gray-400">
          <p class="font-semibold text-gray-700">Thank you for your purchase!</p>
          <p class="mt-1">Online Shop &mdash; Quality products delivered to your door.</p>
          <p class="mt-1">www.onlineshop.com | Tel: +855 12 345 678</p>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button @click="downloadPDF" class="bg-gray-900 text-white font-bold py-3 px-8 rounded-lg hover:bg-gray-800 transition">
          <i class="fas fa-download mr-2"></i>Download PDF
        </button>
        <router-link to="/orders" class="bg-gray-200 text-gray-900 font-bold py-3 px-8 rounded-lg hover:bg-gray-300 transition text-center">
          Back to Orders
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ReceiptPage',
  data() {
    return {
      order: null,
      loading: true,
      error: '',
    }
  },
  computed: {
    subtotal() {
      if (!this.order?.items) return 0
      return this.order.items.reduce((sum, item) => sum + item.quantity * item.price, 0)
    },
    tax() {
      return this.subtotal * 0.1
    },
  },
  async mounted() {
    await this.fetchOrder()
  },
  methods: {
    async fetchOrder() {
      this.loading = true
      try {
        const token = localStorage.getItem('token')
        const id = this.$route.params.id
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/orders/${id}`, {
          headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
        })
        if (res.ok) {
          this.order = await res.json()
        } else {
          this.error = 'Unable to load receipt.'
        }
      } catch {
        this.error = 'Unable to load receipt.'
      } finally {
        this.loading = false
      }
    },
    downloadPDF() {
      window.print()
    },
    printReceipt() {
      window.print()
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric',
      })
    },
    capitalize(str) {
      return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
    },
    paymentStatusClass(status) {
      const classes = {
        unpaid: 'bg-yellow-100 text-yellow-800',
        pending_verification: 'bg-blue-200 text-blue-700',
        verified: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800',
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    },
  },
}
</script>

<style scoped>
@media print {
  @page { margin: 0.5in; }
  body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  nav, footer, .no-print { display: none !important; }
  #receipt { box-shadow: none; border: none; padding: 0; }
  #receipt img { max-width: 100%; }
}
</style>
