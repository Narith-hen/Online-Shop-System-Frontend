<template>
  <div>
    <!-- Header -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 px-4">
      <div class="max-w-7xl mx-auto animate-fade-in-down">
        <h1 class="text-4xl font-bold mb-2">My Orders</h1>
        <p class="text-lg">Track and manage all your orders in one place</p>
      </div>
    </section>

    <!-- Orders Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">

      <!-- Filter/Search -->
      <div class="mb-8 flex flex-col md:flex-row gap-4 animate-fade-in-up">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by order ID or product name..."
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
        />
        <select v-model="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
          <option value="">All Orders</option>
          <option value="pending">Pending</option>
          <option value="processing">Processing</option>
          <option value="shipped">Shipped</option>
          <option value="delivered">Delivered</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
        <p class="text-gray-500">Loading your orders...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredOrders.length === 0" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
          <i class="fas fa-shopping-bag text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No orders found</h3>
        <p class="text-gray-500 mb-6">
          {{ searchQuery || statusFilter ? 'Try adjusting your search or filter.' : "You haven't placed any orders yet." }}
        </p>
        <router-link
          v-if="!searchQuery && !statusFilter"
          to="/products"
          class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition"
        >
          Browse Products
        </router-link>
      </div>

      <!-- Orders List -->
      <div v-else class="space-y-6">
        <div
          v-for="(order, index) in filteredOrders"
          :key="order.id"
          class="bg-white rounded-lg shadow-md overflow-hidden animate-fade-in-up"
          :style="{ animationDelay: `${index * 80}ms` }"
        >
          <div class="px-6 py-4 border-l-4" :class="statusBorderClass(order.status)">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="text-lg font-bold text-gray-900">Order #{{ order.id }}</h3>
                <p class="text-sm text-gray-600">Placed on {{ formatDate(order.created_at) }}</p>
              </div>
              <span class="px-4 py-1 rounded-full text-sm font-semibold" :class="statusBadgeClass(order.status)">
                {{ statusIcon(order.status) }} {{ capitalize(order.status) }}
              </span>
            </div>
          </div>

          <div class="p-6">
            <!-- Items -->
            <div class="mb-6">
              <h4 class="font-bold text-gray-900 mb-3">Items Ordered:</h4>
              <div class="space-y-3">
                <div v-for="item in order.items" :key="item.id" class="flex gap-4 items-start">
                  <div class="w-20 h-20 bg-gray-100 rounded flex items-center justify-center shrink-0">
                    <img
                      v-if="item.product?.image_url"
                      :src="item.product.image_url"
                      :alt="item.product.name"
                      class="w-full h-full object-cover rounded"
                    />
                    <i v-else class="fas fa-box text-gray-400 text-2xl"></i>
                  </div>
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">{{ item.product?.name || 'Product' }}</p>
                    <p class="text-sm text-gray-600">Qty: {{ item.quantity }} &times; ${{ item.price?.toFixed(2) }}</p>
                    <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded" :class="itemStatusClass(item.status)">
                      {{ capitalize(item.status || 'pending') }}
                    </span>
                  </div>
                  <div class="text-right">
                    <p class="font-semibold text-gray-900">${{ (item.quantity * item.price).toFixed(2) }}</p>
                    <div class="mt-1 flex flex-col gap-1">
                      <button
                        v-if="item.status === 'pending'"
                        @click="cancelItem(order, item)"
                        :disabled="processingItemId === item.id"
                        class="text-xs text-red-600 hover:text-red-800 font-semibold disabled:opacity-50"
                      >
                        <i v-if="processingItemId === item.id" class="fas fa-spinner fa-spin mr-1"></i>
                        Cancel
                      </button>
                      <button
                        v-if="item.status === 'delivered' || item.status === 'completed'"
                        @click="returnItem(order, item)"
                        :disabled="processingItemId === item.id"
                        class="text-xs text-orange-600 hover:text-orange-800 font-semibold disabled:opacity-50"
                      >
                        <i v-if="processingItemId === item.id" class="fas fa-spinner fa-spin mr-1"></i>
                        Return
                      </button>
                      <button
                        v-if="item.status === 'cancelled' || item.status === 'returned'"
                        @click="reorderItem(order, item)"
                        :disabled="processingItemId === item.id"
                        class="text-xs text-blue-600 hover:text-blue-800 font-semibold disabled:opacity-50"
                      >
                        <i v-if="processingItemId === item.id" class="fas fa-spinner fa-spin mr-1"></i>
                        Buy Again
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <hr class="my-4" />

            <!-- Total -->
            <div class="flex justify-end mb-6">
              <div class="text-right">
                <p class="text-sm text-gray-600">Order Total</p>
                <p class="text-lg font-bold text-blue-600">${{ order.total?.toFixed(2) || '0.00' }}</p>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col md:flex-row gap-3">
              <router-link
                :to="`/orders/${order.id}/receipt`"
                class="flex-1 bg-gray-900 text-white font-bold py-2 rounded-lg hover:bg-gray-800 transition text-center text-sm"
              >
                <i class="fas fa-receipt mr-1"></i> Receipt
              </router-link>
              <button
                @click="toggleDetail(order)"
                class="flex-1 bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition"
              >
                {{ expandedOrderId === order.id ? 'Hide Details' : 'View Details' }}
              </button>
              <button v-if="order.status === 'shipped'" class="flex-1 bg-gray-200 text-gray-900 font-bold py-2 rounded-lg hover:bg-gray-300 transition">
                Track Shipment
              </button>
              <button
                v-if="order.status === 'pending'"
                @click="cancelOrder(order)"
                :disabled="cancellingId === order.id"
                class="flex-1 border border-red-600 text-red-600 font-bold py-2 rounded-lg hover:bg-red-50 transition disabled:opacity-50"
              >
                <i v-if="cancellingId === order.id" class="fas fa-spinner fa-spin mr-1"></i>
                {{ cancellingId === order.id ? 'Cancelling...' : 'Cancel Order' }}
              </button>
            </div>

            <!-- Expanded Details -->
            <div v-if="expandedOrderId === order.id" class="mt-4 pt-4 border-t">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                  <p class="font-semibold text-gray-900 mb-1">Order Information</p>
                  <p class="text-gray-600">Order ID: #{{ order.id }}</p>
                  <p class="text-gray-600">Date: {{ formatDate(order.created_at) }}</p>
                  <p class="text-gray-600">Status: {{ capitalize(order.status) }}</p>
                  <p class="text-gray-600" v-if="order.payment_method">
                    Payment: {{ order.payment_method === 'aba' ? 'ABA Bank' : 'ACLEDA Bank' }}
                    <span
                      class="inline-block ml-1 text-xs font-semibold px-2 py-0.5 rounded"
                      :class="paymentStatusClass(order.payment_status)"
                    >{{ capitalize(order.payment_status || 'unpaid') }}</span>
                  </p>
                </div>
                <div>
                  <p class="font-semibold text-gray-900 mb-1">Customer Information</p>
                  <p class="text-gray-600">Name: {{ order.customer_name || order.user?.name || 'N/A' }}</p>
                  <p class="text-gray-600">Email: {{ order.customer_email || order.user?.email || 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                  <p class="font-semibold text-gray-900 mb-1">Order Summary</p>
                  <div class="space-y-1">
                    <div v-for="item in order.items" :key="item.id" class="flex justify-between">
                      <span class="text-gray-600">{{ item.product?.name || 'Product' }} x{{ item.quantity }}</span>
                      <span class="text-gray-900">${{ (item.quantity * item.price).toFixed(2) }}</span>
                    </div>
                    <hr class="my-2" />
                    <div class="flex justify-between font-bold">
                      <span>Total</span>
                      <span class="text-blue-600">${{ order.total?.toFixed(2) || '0.00' }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.last_page > 1" class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
          <p class="text-sm text-gray-600">
            Showing {{ orders.length }} of {{ pagination.total }} orders
          </p>
          <div class="flex items-center gap-2">
            <button
              @click="goToPage(pagination.current_page - 1)"
              :disabled="pagination.current_page <= 1 || paginating"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <i v-if="paginating" class="fas fa-spinner fa-spin mr-1"></i>
              Previous
            </button>
            <template v-for="page in pageNumbers" :key="page">
              <span v-if="page === '...'" class="px-2 text-gray-400">...</span>
              <button
                v-else
                @click="goToPage(page)"
                :disabled="paginating"
                class="w-10 h-10 rounded-lg font-semibold transition"
                :class="page === pagination.current_page
                  ? 'bg-blue-600 text-white'
                  : 'border border-gray-300 hover:bg-gray-100 text-gray-700'"
              >
                {{ page }}
              </button>
            </template>
            <button
              @click="goToPage(pagination.current_page + 1)"
              :disabled="pagination.current_page >= pagination.last_page || paginating"
              class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next
              <i v-if="paginating" class="fas fa-spinner fa-spin ml-1"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrderCard',
  data() {
    return {
      orders: [],
      loading: true,
      searchQuery: '',
      statusFilter: '',
      cancellingId: null,
      processingItemId: null,
      expandedOrderId: null,
      paginating: false,
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0,
      },
    }
  },
  computed: {
    filteredOrders() {
      return this.orders.filter(order => {
        const matchesSearch = !this.searchQuery ||
          String(order.id).includes(this.searchQuery) ||
          (order.items || []).some(item =>
            item.product?.name?.toLowerCase().includes(this.searchQuery.toLowerCase())
          )
        const matchesStatus = !this.statusFilter || order.status === this.statusFilter
        return matchesSearch && matchesStatus
      })
    },
    pageNumbers() {
      const current = this.pagination.current_page
      const last = this.pagination.last_page
      if (last <= 7) {
        return Array.from({ length: last }, (_, i) => i + 1)
      }
      const pages = []
      pages.push(1)
      if (current > 3) pages.push('...')
      const start = Math.max(2, current - 1)
      const end = Math.min(last - 1, current + 1)
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      if (current < last - 2) pages.push('...')
      if (last > 1) pages.push(last)
      return pages
    },
  },
  async mounted() {
    await this.fetchOrders()
  },
  methods: {
    async fetchOrders(page = 1) {
      this.loading = true
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/orders?page=${page}`, {
          headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
        })
        if (res.ok) {
          const data = await res.json()
          if (data.data) {
            this.orders = data.data
            this.pagination = {
              current_page: data.current_page || data.meta?.current_page || 1,
              last_page: data.last_page || data.meta?.last_page || 1,
              per_page: data.per_page || data.meta?.per_page || 15,
              total: data.total || data.meta?.total || 0,
            }
          } else {
            this.orders = data || []
          }
        }
      } catch {
        this.orders = []
      } finally {
        this.loading = false
      }
    },
    async goToPage(page) {
      if (page < 1 || page > this.pagination.last_page) return
      this.paginating = true
      await this.fetchOrders(page)
      this.paginating = false
      window.scrollTo({ top: 0, behavior: 'smooth' })
    },
    toggleDetail(order) {
      this.expandedOrderId = this.expandedOrderId === order.id ? null : order.id
    },
    async cancelItem(order, item) {
      if (this.processingItemId) return
      if (!confirm(`Cancel "${item.product?.name || 'this item'}" from order #${order.id}?`)) return
      this.processingItemId = item.id
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/orders/${order.id}/items/${item.id}/cancel`, {
          method: 'POST',
          headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json', Accept: 'application/json' },
        })
        if (res.ok) {
          const data = await res.json()
          const idx = this.orders.findIndex(o => o.id === order.id)
          if (idx !== -1) this.orders[idx] = data.order
        } else {
          const data = await res.json()
          alert(data.message || 'Failed to cancel item.')
        }
      } catch {
        alert('Failed to cancel item.')
      } finally {
        this.processingItemId = null
      }
    },
    async returnItem(order, item) {
      if (this.processingItemId) return
      if (!confirm(`Request return for "${item.product?.name || 'this item'}"?`)) return
      this.processingItemId = item.id
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/orders/${order.id}/items/${item.id}/return`, {
          method: 'POST',
          headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json', Accept: 'application/json' },
        })
        if (res.ok) {
          const data = await res.json()
          const idx = this.orders.findIndex(o => o.id === order.id)
          if (idx !== -1) this.orders[idx] = data.order
        } else {
          const data = await res.json()
          alert(data.message || 'Failed to process return.')
        }
      } catch {
        alert('Failed to process return.')
      } finally {
        this.processingItemId = null
      }
    },
    async reorderItem(order, item) {
      if (this.processingItemId) return
      this.processingItemId = item.id
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/orders/${order.id}/items/${item.id}/reorder`, {
          method: 'POST',
          headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json', Accept: 'application/json' },
        })
        if (res.ok) {
          alert('Item added to your cart!')
        } else {
          const data = await res.json()
          alert(data.message || 'Failed to add to cart.')
        }
      } catch {
        alert('Failed to add to cart.')
      } finally {
        this.processingItemId = null
      }
    },
    async cancelOrder(order) {
      if (this.cancellingId) return
      if (!confirm(`Are you sure you want to cancel order #${order.id}?`)) return

      this.cancellingId = order.id
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/orders/${order.id}/cancel`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
        })
        if (res.ok) {
          const data = await res.json()
          const index = this.orders.findIndex(o => o.id === order.id)
          if (index !== -1) {
            this.orders[index] = data.order
          }
        } else {
          const data = await res.json()
          alert(data.message || 'Failed to cancel order.')
        }
      } catch {
        alert('Failed to cancel order. Please try again.')
      } finally {
        this.cancellingId = null
      }
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
        pending_verification: 'bg-blue-100 text-blue-800',
        verified: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800',
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    },
    itemStatusClass(status) {
      const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        shipped: 'bg-indigo-100 text-indigo-800',
        delivered: 'bg-green-100 text-green-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
        returned: 'bg-orange-100 text-orange-800',
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    },
    statusIcon(status) {
      const icons = { delivered: '\u2713', shipped: '\ud83d\udce6', processing: '\u23f3', pending: '\u23f1', cancelled: '\u2715' }
      return icons[status] || ''
    },
    statusBorderClass(status) {
      const classes = {
        delivered: 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-500',
        shipped: 'bg-gradient-to-r from-blue-50 to-cyan-50 border-blue-500',
        processing: 'bg-gradient-to-r from-yellow-50 to-orange-50 border-yellow-500',
        pending: 'bg-gradient-to-r from-yellow-50 to-orange-50 border-yellow-500',
        cancelled: 'bg-gradient-to-r from-red-50 to-pink-50 border-red-500',
      }
      return classes[status] || 'bg-gray-50 border-gray-400'
    },
    statusBadgeClass(status) {
      const classes = {
        delivered: 'bg-green-100 text-green-800',
        shipped: 'bg-blue-100 text-blue-800',
        processing: 'bg-yellow-100 text-yellow-800',
        pending: 'bg-yellow-100 text-yellow-800',
        cancelled: 'bg-red-100 text-red-800',
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    },
  },
}
</script>
