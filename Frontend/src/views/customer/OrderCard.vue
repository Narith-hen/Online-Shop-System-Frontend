<template>
  <div>
    <section class="relative text-white py-24 px-4" style="background-image: url('https://www.inboundlogistics.com/wp-content/uploads/order-processing.jpg'); background-size: cover; background-position: center;">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
      <div class="max-w-7xl mx-auto animate-fade-in-down relative text-center">
        <h1 class="text-5xl font-extrabold mb-3 drop-shadow-lg">My Orders</h1>
        <p class="text-xl text-white/80 font-light max-w-xl mx-auto">Track and manage all your orders</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-12">
      <div class="mb-8 flex flex-col md:flex-row gap-4 animate-fade-in-up">
        <div class="relative flex-1">
          <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input v-model="searchQuery" type="text" placeholder="Search order ID or product..."
            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition" />
        </div>
        <select v-model="statusFilter"
          class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition bg-white">
          <option value="">All Orders</option>
          <option value="pending">Pending</option>
          <option value="processing">Processing</option>
          <option value="shipped">Shipped</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>

      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div v-for="i in 6" :key="i" class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 animate-fade-in-up" :style="{ animationDelay: `${i * 60}ms` }">
          <div class="p-5 space-y-3">
            <div class="flex justify-between items-center"><div class="skeleton skeleton-text-lg w-28"></div><div class="skeleton skeleton-badge"></div></div>
            <div class="skeleton skeleton-text-sm w-40"></div>
            <div class="skeleton skeleton-image h-20 rounded-xl"></div>
            <div class="flex justify-between"><div class="skeleton skeleton-text-sm w-16"></div><div class="skeleton skeleton-text-lg w-20"></div></div>
          </div>
        </div>
      </div>

      <div v-else-if="filteredOrders.length === 0" class="text-center py-20 animate-fade-in">
        <div class="inline-flex items-center justify-center w-28 h-28 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full mb-6">
          <i class="fas fa-receipt text-5xl text-blue-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">No orders found</h3>
        <p class="text-gray-500 mb-8">{{ searchQuery || statusFilter ? 'Try adjusting your search or filter.' : "You haven't placed any orders yet." }}</p>
        <router-link v-if="!searchQuery && !statusFilter" to="/products" class="btn-primary text-base py-3 px-8"><i class="fas fa-arrow-left mr-1"></i> Browse Products</router-link>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div v-for="(order, index) in filteredOrders" :key="order.id"
          @click="openReview(order)"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer animate-fade-in-up hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
          :style="{ animationDelay: `${index * 60}ms` }">
          <div class="h-2" :class="statusBgClass(order.status)"></div>
          <div class="p-5">
            <div class="flex items-center justify-between mb-3">
              <h3 class="font-bold text-gray-900">Order #{{ order.id }}</h3>
              <span class="status-badge text-[10px]" :class="statusBadgeClass(order.status)">{{ capitalize(order.status) }}</span>
            </div>
            <p class="text-xs text-gray-500 mb-4"><i class="far fa-calendar mr-1"></i>{{ formatDate(order.created_at) }}</p>

            <div class="flex items-center gap-2 mb-4">
              <div v-for="item in order.items.slice(0, 3)" :key="item.id" class="w-10 h-10 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 -mr-2 last:mr-0 ring-2 ring-white">
                <img v-if="item.product?.image_url" :src="item.product.image_url" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-box text-xs"></i></div>
              </div>
              <span v-if="order.items.length > 3" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500 ring-2 ring-white">+{{ order.items.length - 3 }}</span>
            </div>

            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">{{ order.items.length }} {{ order.items.length === 1 ? 'item' : 'items' }}</span>
              <span class="text-lg font-bold text-blue-500">${{ Number(order.total).toFixed(2) }}</span>
            </div>
          </div>
        </div>
      </div>

      <div v-if="pagination.last_page > 1" class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 mt-8 border-t border-gray-100">
        <p class="text-sm text-gray-500">Page {{ pagination.current_page }} of {{ pagination.last_page }} ({{ pagination.total }} orders)</p>
        <div class="flex items-center gap-1.5">
          <button @click="goToPage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-medium transition disabled:opacity-30 disabled:cursor-not-allowed border border-gray-300 text-gray-600 hover:bg-gray-100 hover:text-gray-900"><i class="fas fa-chevron-left text-xs"></i></button>
          <template v-for="(page, idx) in pageNumbers" :key="'p' + idx">
            <span v-if="page === '...'" class="px-1 text-gray-400 text-sm select-none">...</span>
            <button v-else @click="goToPage(page)"
              class="w-10 h-10 rounded-xl text-sm font-semibold transition-all duration-150 flex items-center justify-center"
              :class="page === pagination.current_page ? 'bg-gray-900 text-white shadow-md shadow-gray-900/20 scale-105' : 'border border-gray-300 text-gray-600 hover:bg-gray-100 hover:text-gray-900 hover:border-gray-400'">{{ page }}</button>
          </template>
          <button @click="goToPage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-medium transition disabled:opacity-30 disabled:cursor-not-allowed border border-gray-300 text-gray-600 hover:bg-gray-100 hover:text-gray-900"><i class="fas fa-chevron-right text-xs"></i></button>
        </div>
      </div>
    </div>

    <Teleport to="body">
      <transition name="modal">
        <div v-if="reviewOrder" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" @click.self="reviewOrder = null">
          <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto animate-scale-in">
            <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-base" :class="statusIconBgClass(reviewOrder.status)">
                  <i :class="statusIconClass(reviewOrder.status)"></i>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-gray-900">Order #{{ reviewOrder.id }}</h3>
                  <p class="text-xs text-gray-500">{{ formatDate(reviewOrder.created_at) }}</p>
                </div>
              </div>
              <button @click="reviewOrder = null" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition"><i class="fas fa-times"></i></button>
            </div>

            <div class="p-6 space-y-5">
              <div class="flex items-center justify-between">
                <span class="status-badge" :class="statusBadgeClass(reviewOrder.status)">{{ capitalize(reviewOrder.status) }}</span>
                <span class="text-2xl font-bold text-blue-500">${{ Number(reviewOrder.total).toFixed(2) }}</span>
              </div>

              <div class="divide-y divide-gray-100">
                <div v-for="item in reviewOrder.items" :key="item.id" class="py-3 flex items-center gap-3">
                  <div class="w-14 h-14 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 shrink-0">
                    <img v-if="item.product?.image_url" :src="item.product.image_url" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-box"></i></div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">{{ item.product?.name || 'Product' }}</p>
                    <p class="text-xs text-gray-500">Qty: {{ item.quantity }} &times; ${{ Number(item.price).toFixed(2) }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-bold text-gray-900">${{ (item.quantity * item.price).toFixed(2) }}</p>
                    <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded" :class="itemStatusClass(item.status)">{{ capitalize(item.status || 'pending') }}</span>
                  </div>
                </div>
              </div>

              <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                <p class="font-semibold text-gray-900 text-sm">Order Summary</p>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span class="font-medium">${{ Number(reviewOrder.total).toFixed(2) }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Shipping</span><span class="text-emerald-600 font-medium">Free</span></div>
                <hr class="border-gray-200" />
                <div class="flex justify-between font-bold"><span class="text-gray-900">Total</span><span class="text-blue-500">${{ Number(reviewOrder.total).toFixed(2) }}</span></div>
              </div>

              <div class="bg-gray-50 rounded-xl p-4 space-y-1.5">
                <p class="font-semibold text-gray-900 text-sm">Shipping To</p>
                <p class="text-sm text-gray-600">{{ reviewOrder.customer_name || reviewOrder.user?.name || 'N/A' }}</p>
                <p class="text-xs text-gray-500">{{ reviewOrder.customer_email || reviewOrder.user?.email || '' }}</p>
                <p v-if="reviewOrder.shipping_name" class="text-xs text-gray-500">{{ reviewOrder.shipping_address }}, {{ reviewOrder.shipping_city }}</p>
              </div>

              <div v-if="reviewOrder.payment_method" class="bg-gray-50 rounded-xl p-4 space-y-1.5">
                <p class="font-semibold text-gray-900 text-sm">Payment</p>
                <p class="text-sm text-gray-600 capitalize">{{ reviewOrder.payment_method }}</p>
                <span class="status-badge text-[10px]" :class="paymentStatusClass(reviewOrder.payment_status)">{{ capitalize(reviewOrder.payment_status || 'unpaid') }}</span>
              </div>

              <div class="flex flex-wrap gap-2 pt-2">
                <router-link v-if="reviewOrder.status !== 'pending'" :to="`/orders/${reviewOrder.id}/receipt`" class="flex-1 px-4 py-2.5 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-600 transition text-sm text-center"><i class="fas fa-receipt mr-1"></i> View Receipt</router-link>
                <button v-if="reviewOrder.status === 'pending'" @click="confirmCancelOrder(reviewOrder)" :disabled="cancellingId === reviewOrder.id" class="flex-1 px-4 py-2.5 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition text-sm"><i class="fas fa-ban mr-1"></i> Cancel Order</button>
                <button @click="reviewOrder = null" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition text-sm">Close</button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </Teleport>

    <Teleport to="body">
      <transition name="modal">
        <div v-if="confirmDialog.visible" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" @click.self="closeConfirm">
          <div class="absolute inset-0 bg-black/40"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center animate-scale-in">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" :class="confirmDialog.danger ? 'bg-red-100' : 'bg-blue-100'">
              <i class="text-3xl" :class="confirmDialog.danger ? 'fas fa-exclamation-triangle text-red-500' : 'fas fa-question-circle text-blue-500'"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ confirmDialog.title }}</h3>
            <p class="text-sm text-gray-600 mb-6">{{ confirmDialog.message }}</p>
            <div class="flex gap-3">
              <button @click="closeConfirm" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Cancel</button>
              <button @click="executeConfirm" :disabled="confirmDialog.processing" class="flex-1 px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition disabled:opacity-50" :class="confirmDialog.danger ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-500 hover:bg-blue-600'">
                <i v-if="confirmDialog.processing" class="fas fa-spinner fa-spin mr-1"></i>{{ confirmDialog.processing ? 'Processing...' : 'Confirm' }}
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
      orders: [], loading: true, searchQuery: '', statusFilter: '',
      cancellingId: null, processingItemId: null, reviewOrder: null,
      pagination: { current_page: 1, last_page: 1, per_page: 15, total: 0 },
      confirmDialog: { visible: false, title: '', message: '', danger: false, processing: false, resolve: null },
    }
  },
  computed: {
    filteredOrders() {
      return this.orders.filter(o => {
        const q = this.searchQuery.toLowerCase().trim()
        return (!q || String(o.id).includes(q) || (o.items || []).some(i => i.product?.name?.toLowerCase().includes(q))) && (!this.statusFilter || o.status === this.statusFilter)
      })
    },
    pageNumbers() {
      const c = this.pagination.current_page, l = this.pagination.last_page
      if (l <= 3) return Array.from({ length: l }, (_, i) => i + 1)
      const start = Math.max(1, c - 1)
      const end = Math.min(l, c + 1)
      const pages = []
      if (start > 1) pages.push('...')
      for (let i = start; i <= end; i++) pages.push(i)
      if (end < l) pages.push('...')
      return pages
    },
  },
  async mounted() { await this.fetchOrders() },
  methods: {
    async fetchOrders(page = 1) {
      this.loading = true
      try {
        const data = await get(`/api/orders?page=${page}`)
        if (data.data) { this.orders = data.data; this.pagination = { current_page: data.current_page || data.meta?.current_page || 1, last_page: data.last_page || data.meta?.last_page || 1, per_page: data.per_page || data.meta?.per_page || 15, total: data.total || data.meta?.total || 0 } }
        else { this.orders = data || [] }
      } catch { this.orders = [] }
      finally { this.loading = false }
    },
    goToPage(p) { if (p < 1 || p > this.pagination.last_page) return; this.fetchOrders(p); window.scrollTo({ top: 0, behavior: 'smooth' }) },
    openReview(order) { this.reviewOrder = order },
    showConfirm(title, message, danger = false) { return new Promise(r => { this.confirmDialog = { visible: true, title, message, danger, processing: false, resolve: r } }) },
    closeConfirm() { this.confirmDialog.visible = false; if (this.confirmDialog.resolve) { this.confirmDialog.resolve(false); this.confirmDialog.resolve = null } },
    async executeConfirm() { this.confirmDialog.processing = true; if (this.confirmDialog.resolve) { await this.confirmDialog.resolve(true); this.confirmDialog.resolve = null } this.confirmDialog.visible = false; this.confirmDialog.processing = false },
    confirmCancelOrder(order) {
      this.showConfirm('Cancel Order', `Cancel Order #${order.id}? This cannot be undone.`, true).then(async c => {
        if (!c) return; this.cancellingId = order.id
        try { const d = await post(`/api/orders/${order.id}/cancel`); const i = this.orders.findIndex(o => o.id === order.id); if (i !== -1) this.orders[i] = d.order; this.reviewOrder = d.order; this.$refs.toastRef?.show({ type: 'success', title: 'Cancelled', message: 'Order cancelled.' }) } catch (e) { this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: e.data?.message || 'Failed.' }) }
        finally { this.cancellingId = null }
      })
    },
    formatDate(d) { return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) },
    capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '' },
    statusBgClass(s) { return { pending: 'bg-amber-400', processing: 'bg-blue-400', shipped: 'bg-purple-400', delivered: 'bg-emerald-400', cancelled: 'bg-red-400' }[s] || 'bg-gray-300' },
    statusBadgeClass(s) { return { pending: 'status-pending', processing: 'status-processing', shipped: 'status-shipped', delivered: 'status-delivered', completed: 'status-completed', cancelled: 'status-cancelled' }[s] || 'bg-gray-100 text-gray-800' },
    statusIconClass(s) { return { pending: 'fas fa-clock', processing: 'fas fa-gear', shipped: 'fas fa-truck', delivered: 'fas fa-circle-check', completed: 'fas fa-circle-check', cancelled: 'fas fa-circle-xmark' }[s] || 'fas fa-circle-info' },
    statusIconBgClass(s) { return { pending: 'bg-amber-100 text-amber-600', processing: 'bg-blue-200 text-blue-500', shipped: 'bg-purple-100 text-purple-600', delivered: 'bg-emerald-100 text-emerald-600', completed: 'bg-emerald-100 text-emerald-600', cancelled: 'bg-red-100 text-red-600' }[s] || 'bg-gray-100 text-gray-600' },
    paymentStatusClass(s) { return { unpaid: 'status-unpaid', pending_verification: 'status-pending_verification', verified: 'status-verified', failed: 'status-failed' }[s] || 'bg-gray-100 text-gray-800' },
    itemStatusClass(s) { return { pending: 'status-pending', processing: 'status-processing', shipped: 'status-shipped', delivered: 'status-delivered', completed: 'status-completed', cancelled: 'status-cancelled', returned: 'status-returned' }[s] || 'bg-gray-100 text-gray-800' },
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
