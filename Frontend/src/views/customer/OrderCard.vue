<template>
  <div>
    <section class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-12 px-4">
      <div class="max-w-7xl mx-auto animate-fade-in-down">
        <h1 class="text-4xl font-bold mb-2">My Orders</h1>
        <p class="text-lg text-blue-100">Track and manage all your orders in one place</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-12">
      <div class="mb-8 flex flex-col md:flex-row gap-4 animate-fade-in-up">
        <div class="relative flex-1">
          <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input v-model="searchQuery" type="text" placeholder="Search by order ID or product name..."
            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition" />
        </div>
        <select v-model="statusFilter"
          class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition appearance-none bg-white">
          <option value="">All Orders</option>
          <option value="pending">Pending</option>
          <option value="processing">Processing</option>
          <option value="shipped">Shipped</option>
          <option value="delivered">Delivered</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>

      <div v-if="loading" class="space-y-6">
        <div v-for="i in 3" :key="i" class="bg-white rounded-xl overflow-hidden shadow-sm animate-fade-in-up" :style="{ animationDelay: `${i * 80}ms` }">
          <div class="p-6 border-l-4 border-gray-200">
            <div class="flex justify-between items-start mb-4">
              <div class="space-y-2">
                <div class="skeleton skeleton-text-lg w-48"></div>
                <div class="skeleton skeleton-text-sm w-32"></div>
              </div>
              <div class="skeleton skeleton-badge"></div>
            </div>
            <div class="space-y-3">
              <div v-for="j in 2" :key="j" class="flex gap-4">
                <div class="skeleton skeleton-image w-20 h-20 rounded-lg shrink-0"></div>
                <div class="flex-1 space-y-2">
                  <div class="skeleton skeleton-text w-3/4"></div>
                  <div class="skeleton skeleton-text-sm w-1/3"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="filteredOrders.length === 0" class="text-center py-20 animate-fade-in">
        <div class="inline-flex items-center justify-center w-28 h-28 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full mb-6">
          <i class="fas fa-receipt text-5xl text-blue-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">No orders found</h3>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">
          {{ searchQuery || statusFilter ? 'Try adjusting your search or filter to find what you\'re looking for.' : 'You haven\'t placed any orders yet. Start shopping to see your orders here.' }}
        </p>
        <router-link v-if="!searchQuery && !statusFilter" to="/products" class="btn-primary text-base py-3 px-8">
          <i class="fas fa-arrow-left mr-1"></i> Browse Products
        </router-link>
      </div>

      <div v-else class="space-y-6">
        <div v-for="(order, index) in filteredOrders" :key="order.id"
          class="bg-white rounded-xl shadow-sm overflow-hidden animate-fade-in-up hover:shadow-md transition-shadow"
          :style="{ animationDelay: `${index * 80}ms` }">
          <div class="px-6 py-4 border-l-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
            :class="statusBorderClass(order.status)">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg"
                :class="statusIconBgClass(order.status)">
                <i :class="statusIconClass(order.status)"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900">Order #{{ order.id }}</h3>
                <p class="text-sm text-gray-500">Placed on {{ formatDate(order.created_at) }}</p>
              </div>
            </div>
            <span class="status-badge shrink-0" :class="statusBadgeClass(order.status)">
              {{ capitalize(order.status) }}
            </span>
          </div>

          <div class="px-6 py-5">
            <div class="space-y-3">
              <div v-for="item in order.items" :key="item.id" class="flex gap-4 items-start py-2">
                <div class="w-20 h-20 bg-gray-50 rounded-lg flex items-center justify-center shrink-0 overflow-hidden border border-gray-100">
                  <img v-if="item.product?.image_url" :src="item.product.image_url" :alt="item.product.name"
                    class="w-full h-full object-cover" />
                  <i v-else class="fas fa-box text-gray-300 text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-gray-900 truncate">{{ item.product?.name || 'Product' }}</p>
                  <p class="text-sm text-gray-500 mt-0.5">Qty: {{ item.quantity }} &times; ${{ Number(item.price).toFixed(2) }}</p>
                  <span class="inline-block mt-1.5 status-badge text-[11px]" :class="itemStatusClass(item.status)">
                    {{ capitalize(item.status || 'pending') }}
                  </span>
                </div>
                <div class="text-right shrink-0">
                  <p class="font-semibold text-gray-900">${{ (item.quantity * item.price).toFixed(2) }}</p>
                  <div class="mt-2 flex flex-col gap-1">
                    <button v-if="item.status === 'pending'"
                      @click="confirmItemAction(order, item, 'cancel')"
                      :disabled="processingItemId === item.id"
                      class="text-xs text-red-600 hover:text-red-800 font-semibold disabled:opacity-50 flex items-center gap-1 justify-end">
                      <i v-if="processingItemId === item.id" class="fas fa-spinner fa-spin"></i>
                      <i v-else class="fas fa-xmark"></i> Cancel
                    </button>
                    <button v-if="item.status === 'delivered' || item.status === 'completed'"
                      @click="confirmItemAction(order, item, 'return')"
                      :disabled="processingItemId === item.id"
                      class="text-xs text-orange-600 hover:text-orange-800 font-semibold disabled:opacity-50 flex items-center gap-1 justify-end">
                      <i v-if="processingItemId === item.id" class="fas fa-spinner fa-spin"></i>
                      <i v-else class="fas fa-rotate-left"></i> Return
                    </button>
                    <button v-if="item.status === 'cancelled' || item.status === 'returned'"
                      @click="reorderItem(order, item)"
                      :disabled="processingItemId === item.id"
                      class="text-xs text-blue-500 hover:text-blue-600 font-semibold disabled:opacity-50 flex items-center gap-1 justify-end">
                      <i v-if="processingItemId === item.id" class="fas fa-spinner fa-spin"></i>
                      <i v-else class="fas fa-cart-plus"></i> Buy Again
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <hr class="my-4" />

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <p class="text-sm text-gray-500">Order Total</p>
                <p class="text-2xl font-bold text-blue-500">${{ Number(order.total).toFixed(2) }}</p>
              </div>
              <div class="flex flex-wrap gap-2">
                <router-link :to="`/orders/${order.id}/receipt`"
                  class="btn-secondary text-sm py-2 px-4">
                  <i class="fas fa-receipt mr-1"></i> Receipt
                </router-link>
                <button @click="toggleDetail(order)"
                  class="btn-secondary text-sm py-2 px-4">
                  <i :class="expandedOrderId === order.id ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="mr-1"></i>
                  {{ expandedOrderId === order.id ? 'Less' : 'Details' }}
                </button>
                <button v-if="order.status === 'pending'"
                  @click="confirmCancelOrder(order)"
                  :disabled="cancellingId === order.id"
                  class="btn-outline-danger text-sm py-2 px-4">
                  <i v-if="cancellingId === order.id" class="fas fa-spinner fa-spin mr-1"></i>
                  <i v-else class="fas fa-ban mr-1"></i>
                  Cancel Order
                </button>
              </div>
            </div>

            <div v-if="expandedOrderId === order.id" class="mt-6 pt-6 border-t border-gray-100 animate-fade-in">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div class="space-y-2">
                  <h4 class="font-bold text-gray-900 text-base mb-2"><i class="fas fa-info-circle text-blue-500 mr-1"></i> Order Information</h4>
                  <p class="text-gray-600 flex justify-between"><span>Order ID</span><span class="font-medium text-gray-900">#{{ order.id }}</span></p>
                  <p class="text-gray-600 flex justify-between"><span>Date</span><span class="font-medium text-gray-900">{{ formatDate(order.created_at) }}</span></p>
                  <p class="text-gray-600 flex justify-between"><span>Status</span><span class="status-badge text-[11px]" :class="statusBadgeClass(order.status)">{{ capitalize(order.status) }}</span></p>
                  <p v-if="order.payment_method" class="text-gray-600 flex justify-between">
                    <span>Payment</span>
                    <span class="font-medium text-gray-900">
                      {{ order.payment_method === 'aba' ? 'ABA Bank' : (order.payment_method === 'acleda' ? 'ACLEDA Bank' : order.payment_method) }}
                      <span class="ml-1 status-badge text-[11px]" :class="paymentStatusClass(order.payment_status)">{{ capitalize(order.payment_status || 'unpaid') }}</span>
                    </span>
                  </p>
                </div>
                <div class="space-y-2">
                  <h4 class="font-bold text-gray-900 text-base mb-2"><i class="fas fa-user text-blue-500 mr-1"></i> Customer Information</h4>
                  <p class="text-gray-600 flex justify-between"><span>Name</span><span class="font-medium text-gray-900">{{ order.customer_name || order.user?.name || 'N/A' }}</span></p>
                  <p class="text-gray-600 flex justify-between"><span>Email</span><span class="font-medium text-gray-900">{{ order.customer_email || order.user?.email || 'N/A' }}</span></p>
                  <p v-if="order.shipping_name" class="text-gray-600 flex justify-between"><span>Shipping</span><span class="font-medium text-gray-900 text-right max-w-[60%]">{{ order.shipping_name }}, {{ order.shipping_address }}, {{ order.shipping_city }}</span></p>
                </div>
                <div class="md:col-span-2">
                  <h4 class="font-bold text-gray-900 text-base mb-3"><i class="fas fa-receipt text-blue-500 mr-1"></i> Order Summary</h4>
                  <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm">
                      <span class="text-gray-600">{{ item.product?.name || 'Product' }} <span class="text-gray-400">x{{ item.quantity }}</span></span>
                      <span class="font-medium text-gray-900">${{ (item.quantity * item.price).toFixed(2) }}</span>
                    </div>
                    <hr class="my-2" />
                    <div class="flex justify-between font-bold text-base">
                      <span>Total</span>
                      <span class="text-blue-500">${{ Number(order.total).toFixed(2) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="pagination.last_page > 1" class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6">
          <p class="text-sm text-gray-500">
            Showing page {{ pagination.current_page }} of {{ pagination.last_page }} ({{ pagination.total }} orders)
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
                :class="page === pagination.current_page ? 'bg-blue-500 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-100'">
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

    <Teleport to="body">
      <transition name="modal">
        <div v-if="confirmDialog.visible" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" @click.self="closeConfirm">
          <div class="absolute inset-0 bg-black/40"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center animate-scale-in">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
              :class="confirmDialog.danger ? 'bg-red-100' : 'bg-blue-100'">
              <i class="text-3xl" :class="confirmDialog.danger ? 'fas fa-exclamation-triangle text-red-500' : 'fas fa-question-circle text-blue-500'"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ confirmDialog.title }}</h3>
            <p class="text-sm text-gray-600 mb-6">{{ confirmDialog.message }}</p>
            <div class="flex gap-3">
              <button @click="closeConfirm" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                Cancel
              </button>
              <button @click="executeConfirm" :disabled="confirmDialog.processing"
                class="flex-1 px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition disabled:opacity-50"
                :class="confirmDialog.danger ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-500 hover:bg-blue-600'">
                <i v-if="confirmDialog.processing" class="fas fa-spinner fa-spin mr-1"></i>
                {{ confirmDialog.processing ? 'Processing...' : 'Confirm' }}
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
import { get, post } from '@/services/api'

export default {
  name: 'OrderCard',
  components: { Toast },
  data() {
    return {
      orders: [],
      loading: true,
      searchQuery: '',
      statusFilter: '',
      cancellingId: null,
      processingItemId: null,
      expandedOrderId: null,
      pagination: { current_page: 1, last_page: 1, per_page: 15, total: 0 },
      confirmDialog: {
        visible: false,
        title: '',
        message: '',
        danger: false,
        processing: false,
        resolve: null,
      },
    }
  },
  computed: {
    filteredOrders() {
      return this.orders.filter(order => {
        const q = this.searchQuery.toLowerCase().trim()
        const matchesSearch = !q || String(order.id).includes(q) || (order.items || []).some(item =>
          item.product?.name?.toLowerCase().includes(q)
        )
        const matchesStatus = !this.statusFilter || order.status === this.statusFilter
        return matchesSearch && matchesStatus
      })
    },
    pageNumbers() {
      const c = this.pagination.current_page
      const l = this.pagination.last_page
      if (l <= 7) return Array.from({ length: l }, (_, i) => i + 1)
      const pages = [1]
      if (c > 3) pages.push('...')
      const start = Math.max(2, c - 1)
      const end = Math.min(l - 1, c + 1)
      for (let i = start; i <= end; i++) pages.push(i)
      if (c < l - 2) pages.push('...')
      if (l > 1) pages.push(l)
      return pages
    },
  },
  async mounted() { await this.fetchOrders() },
  methods: {
    async fetchOrders(page = 1) {
      this.loading = true
      try {
        const data = await get(`/api/orders?page=${page}`)
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
      } catch { this.orders = [] }
      finally { this.loading = false }
    },
    async goToPage(page) {
      if (page < 1 || page > this.pagination.last_page) return
      await this.fetchOrders(page)
      window.scrollTo({ top: 0, behavior: 'smooth' })
    },
    toggleDetail(order) {
      this.expandedOrderId = this.expandedOrderId === order.id ? null : order.id
    },
    showConfirm(title, message, danger = false) {
      return new Promise(resolve => {
        this.confirmDialog = { visible: true, title, message, danger, processing: false, resolve }
      })
    },
    closeConfirm() {
      this.confirmDialog.visible = false
      if (this.confirmDialog.resolve) { this.confirmDialog.resolve(false); this.confirmDialog.resolve = null }
    },
    async executeConfirm() {
      this.confirmDialog.processing = true
      if (this.confirmDialog.resolve) { await this.confirmDialog.resolve(true); this.confirmDialog.resolve = null }
      this.confirmDialog.visible = false
      this.confirmDialog.processing = false
    },
    confirmItemAction(order, item, action) {
      const labels = { cancel: { title: 'Cancel Item', message: `Cancel "${item.product?.name || 'this item'}" from Order #${order.id}?` }, return: { title: 'Return Item', message: `Request a return for "${item.product?.name || 'this item'}"?` } }
      const l = labels[action]
      this.showConfirm(l.title, l.message, action === 'cancel').then(async confirmed => {
        if (!confirmed) return
        this.processingItemId = item.id
        try {
          const endpoint = action === 'cancel' ? 'cancel' : 'return'
          const data = await post(`/api/orders/${order.id}/items/${item.id}/${endpoint}`)
          const idx = this.orders.findIndex(o => o.id === order.id)
          if (idx !== -1) this.orders[idx] = data.order
          const msg = action === 'cancel' ? 'Item cancelled successfully.' : 'Return requested successfully.'
          this.$refs.toastRef?.show({ type: 'success', title: 'Success', message: msg })
        } catch (err) {
          this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: err.data?.message || `Failed to ${action} item.` })
        } finally { this.processingItemId = null }
      })
    },
    async reorderItem(order, item) {
      if (this.processingItemId) return
      this.processingItemId = item.id
      try {
        const data = await post(`/api/orders/${order.id}/items/${item.id}/reorder`)
        this.$refs.toastRef?.show({ type: 'success', title: 'Added to Cart', message: data.message || '"' + item.product?.name + '" has been added to your cart.' })
      } catch (err) {
        this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: err.data?.message || 'Failed to add to cart.' })
      } finally { this.processingItemId = null }
    },
    confirmCancelOrder(order) {
      this.showConfirm('Cancel Order', `Are you sure you want to cancel Order #${order.id}? This action cannot be undone.`, true).then(async confirmed => {
        if (!confirmed) return
        this.cancellingId = order.id
        try {
          const data = await post(`/api/orders/${order.id}/cancel`)
          const idx = this.orders.findIndex(o => o.id === order.id)
          if (idx !== -1) this.orders[idx] = data.order
          this.$refs.toastRef?.show({ type: 'success', title: 'Order Cancelled', message: 'Your order has been cancelled successfully.' })
        } catch (err) {
          this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: err.data?.message || 'Failed to cancel order.' })
        } finally { this.cancellingId = null }
      })
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
    },
    capitalize(str) { return str ? str.charAt(0).toUpperCase() + str.slice(1) : '' },
    statusBorderClass(status) {
      const map = { delivered: 'border-emerald-500', shipped: 'border-blue-500', processing: 'border-amber-500', pending: 'border-amber-400', cancelled: 'border-red-500' }
      return map[status] || 'border-gray-300'
    },
    statusBadgeClass(status) {
      const map = { pending: 'status-pending', processing: 'status-processing', shipped: 'status-shipped', delivered: 'status-delivered', completed: 'status-completed', cancelled: 'status-cancelled' }
      return map[status] || 'bg-gray-100 text-gray-800 border-gray-200'
    },
    statusIconClass(status) {
      const map = { pending: 'fas fa-clock', processing: 'fas fa-gear', shipped: 'fas fa-truck', delivered: 'fas fa-circle-check', completed: 'fas fa-circle-check', cancelled: 'fas fa-circle-xmark' }
      return map[status] || 'fas fa-circle-info'
    },
    statusIconBgClass(status) {
      const map = { pending: 'bg-amber-100 text-amber-600', processing: 'bg-blue-200 text-blue-500', shipped: 'bg-purple-100 text-purple-600', delivered: 'bg-emerald-100 text-emerald-600', completed: 'bg-emerald-100 text-emerald-600', cancelled: 'bg-red-100 text-red-600' }
      return map[status] || 'bg-gray-100 text-gray-600'
    },
    paymentStatusClass(status) {
      const map = { unpaid: 'status-unpaid', pending_verification: 'status-pending_verification', verified: 'status-verified', failed: 'status-failed' }
      return map[status] || 'bg-gray-100 text-gray-800'
    },
    itemStatusClass(status) {
      const map = { pending: 'status-pending', processing: 'status-processing', shipped: 'status-shipped', delivered: 'status-delivered', completed: 'status-completed', cancelled: 'status-cancelled', returned: 'status-returned' }
      return map[status] || 'bg-gray-100 text-gray-800'
    },
  },
}
</script>

<style scoped>
.modal-enter-active { transition: all 0.25s ease; }
.modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from { opacity: 0; }
.modal-leave-to { opacity: 0; }
.animate-scale-in { animation: scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
@keyframes scaleIn { from { opacity: 0; transform: scale(0.92); } to { opacity: 1; transform: scale(1); } }
</style>
