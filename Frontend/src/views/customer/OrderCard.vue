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
          type="text" 
          placeholder="Search by order ID or product name..." 
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
        />
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
          <option>All Orders</option>
          <option>Pending</option>
          <option>Processing</option>
          <option>Shipped</option>
          <option>Delivered</option>
          <option>Cancelled</option>
        </select>
      </div>

      <!-- Orders List -->
      <div class="space-y-6">
        
        <!-- Order Card 1 - Delivered -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="text-lg font-bold text-gray-900">Order #ORD-2024-001</h3>
                <p class="text-sm text-gray-600">Placed on March 15, 2024</p>
              </div>
              <span class="bg-green-100 text-green-800 px-4 py-1 rounded-full text-sm font-semibold">
                ✓ Delivered
              </span>
            </div>
          </div>

          <div class="p-6">
            <div class="mb-6">
              <h4 class="font-bold text-gray-900 mb-3">Items Ordered:</h4>
              <div class="space-y-3">
                <div class="flex gap-4 items-start">
                  <img src="https://via.placeholder.com/80x80" alt="Product" class="w-20 h-20 object-cover rounded" />
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">Premium Headphones</p>
                    <p class="text-sm text-gray-600">Qty: 1 × $79.99</p>
                  </div>
                  <p class="font-semibold text-gray-900">$79.99</p>
                </div>
                <div class="flex gap-4 items-start">
                  <img src="https://via.placeholder.com/80x80" alt="Product" class="w-20 h-20 object-cover rounded" />
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">USB-C Cable</p>
                    <p class="text-sm text-gray-600">Qty: 2 × $12.99</p>
                  </div>
                  <p class="font-semibold text-gray-900">$25.98</p>
                </div>
              </div>
            </div>

            <hr class="my-4" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <div>
                <p class="text-sm text-gray-600 mb-1">Subtotal</p>
                <p class="font-semibold text-gray-900">$105.97</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 mb-1">Shipping</p>
                <p class="font-semibold text-gray-900">Free</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 mb-1">Tax</p>
                <p class="font-semibold text-gray-900">$8.48</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 mb-1">Total</p>
                <p class="text-lg font-bold text-blue-600">$114.45</p>
              </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3">
              <button class="flex-1 bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition">
                View Details
              </button>
              <button class="flex-1 bg-gray-200 text-gray-900 font-bold py-2 rounded-lg hover:bg-gray-300 transition">
                Download Invoice
              </button>
              <button class="flex-1 border border-blue-600 text-blue-600 font-bold py-2 rounded-lg hover:bg-blue-50 transition">
                Leave Review
              </button>
            </div>
          </div>
        </div>

        <!-- Order Card 2 - Shipped -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-6 py-4 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="text-lg font-bold text-gray-900">Order #ORD-2024-002</h3>
                <p class="text-sm text-gray-600">Placed on March 20, 2024</p>
              </div>
              <span class="bg-blue-100 text-blue-800 px-4 py-1 rounded-full text-sm font-semibold">
                📦 Shipped
              </span>
            </div>
          </div>

          <div class="p-6">
            <div class="mb-6">
              <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 65%"></div>
              </div>
              <p class="text-sm text-gray-600">Expected delivery: March 25, 2024</p>
            </div>

            <div class="mb-6">
              <h4 class="font-bold text-gray-900 mb-3">Items Ordered:</h4>
              <div class="flex gap-4 items-start">
                <img src="https://via.placeholder.com/80x80" alt="Product" class="w-20 h-20 object-cover rounded" />
                <div class="flex-1">
                  <p class="font-semibold text-gray-900">Wireless Mouse</p>
                  <p class="text-sm text-gray-600">Qty: 1 × $29.99</p>
                </div>
                <p class="font-semibold text-gray-900">$29.99</p>
              </div>
            </div>

            <hr class="my-4" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <div>
                <p class="text-sm text-gray-600 mb-1">Subtotal</p>
                <p class="font-semibold text-gray-900">$29.99</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 mb-1">Shipping</p>
                <p class="font-semibold text-gray-900">$5.00</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 mb-1">Tax</p>
                <p class="font-semibold text-gray-900">$2.80</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 mb-1">Total</p>
                <p class="text-lg font-bold text-blue-600">$37.79</p>
              </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3">
              <button class="flex-1 bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition">
                Track Shipment
              </button>
              <button class="flex-1 bg-gray-200 text-gray-900 font-bold py-2 rounded-lg hover:bg-gray-300 transition">
                View Details
              </button>
            </div>
          </div>
        </div>

        <!-- Order Card 3 - Processing -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-l-4 border-yellow-500">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="text-lg font-bold text-gray-900">Order #ORD-2024-003</h3>
                <p class="text-sm text-gray-600">Placed on March 22, 2024</p>
              </div>
              <span class="bg-yellow-100 text-yellow-800 px-4 py-1 rounded-full text-sm font-semibold">
                ⏳ Processing
              </span>
            </div>
          </div>

          <div class="p-6">
            <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
              <p class="text-sm text-yellow-800">
                Your order is being prepared for shipment. We'll notify you when it ships!
              </p>
            </div>

            <div class="mb-6">
              <h4 class="font-bold text-gray-900 mb-3">Items Ordered:</h4>
              <div class="flex gap-4 items-start">
                <img src="https://via.placeholder.com/80x80" alt="Product" class="w-20 h-20 object-cover rounded" />
                <div class="flex-1">
                  <p class="font-semibold text-gray-900">Phone Stand</p>
                  <p class="text-sm text-gray-600">Qty: 1 × $15.99</p>
                </div>
                <p class="font-semibold text-gray-900">$15.99</p>
              </div>
            </div>

            <hr class="my-4" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <div>
                <p class="text-sm text-gray-600 mb-1">Subtotal</p>
                <p class="font-semibold text-gray-900">$15.99</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 mb-1">Shipping</p>
                <p class="font-semibold text-gray-900">Free</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 mb-1">Tax</p>
                <p class="font-semibold text-gray-900">$1.28</p>
              </div>
              <div>
                <p class="text-sm text-gray-600 mb-1">Total</p>
                <p class="text-lg font-bold text-blue-600">$17.27</p>
              </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3">
              <button class="flex-1 bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition">
                View Details
              </button>
              <button class="flex-1 border border-red-600 text-red-600 font-bold py-2 rounded-lg hover:bg-red-50 transition">
                Cancel Order
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
  name: 'OrderCard'
}
</script>