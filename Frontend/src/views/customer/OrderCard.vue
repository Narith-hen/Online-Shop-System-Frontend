<template>
  <div>
    <!-- Header -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 px-4">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold mb-2">My Orders</h1>
        <p class="text-lg">Track and manage all your orders in one place</p>
      </div>
    </section>

    <!-- Orders Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">

      <!-- Filter/Search -->
      <div class="mb-8 flex flex-col md:flex-row gap-4">
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
        <div v-for="order in filteredOrders" :key="order.id" class="bg-white rounded-lg shadow-md overflow-hidden">
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
                  <div class="w-20 h-20 bg-gray-100 rounded flex items-center justify-center">
                    <i class="fas fa-box text-gray-400"></i>
                  </div>
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">{{ item.product?.name || 'Product' }}</p>
                    <p class="text-sm text-gray-600">Qty: {{ item.quantity }} &times; ${{ item.price?.toFixed(2) }}</p>
                  </div>
                  <p class="font-semibold text-gray-900">${{ (item.quantity * item.price).toFixed(2) }}</p>
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
              <button class="flex-1 bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition">
                View Details
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
  },
  async mounted() {
    await this.fetchOrders()
  },
  methods: {
    async fetchOrders() {
      this.loading = true
      try {
        const token = localStorage.getItem('token')
        const res = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/orders`, {
          headers: { Authorization: `Bearer ${token}` },
        })
        if (res.ok) {
          const data = await res.json()
          this.orders = data.data || data || []
        }
      } catch {
        this.orders = []
      } finally {
        this.loading = false
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
