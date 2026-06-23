<template>
  <MainLayout></MainLayout>
  <div>
    <!-- Hero Section -->
    <section
      class="relative text-white py-32 px-4"
      :style="{
        backgroundImage: `url(${welcomeImg})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        backgroundRepeat: 'no-repeat',
        minHeight: '50vh',
      }"
    >
      <!-- Dark overlay -->
      <!-- <div class="absolute inset-0 bg-black/40"></div> -->
      <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-5xl font-bold mb-4">Welcome to Online Shop</h1>
        <p class="text-xl mb-8">Discover amazing products at unbeatable prices</p>
        <router-link
          to="/products"
          class="inline-block bg-white text-blue-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition"
        >
          Shop Now
        </router-link>
      </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
          <div class="text-4xl mb-3">🚚</div>
          <h3 class="text-xl font-bold mb-2">Free Shipping</h3>
          <p class="text-gray-600">On orders over $50</p>
        </div>
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
          <div class="text-4xl mb-3">🔒</div>
          <h3 class="text-xl font-bold mb-2">Secure Payment</h3>
          <p class="text-gray-600">100% safe transactions</p>
        </div>
        <div class="text-center p-6 bg-white rounded-lg shadow-md">
          <div class="text-4xl mb-3">♻️</div>
          <h3 class="text-xl font-bold mb-2">Easy Returns</h3>
          <p class="text-gray-600">30-day return policy</p>
        </div>
      </div>
    </section>

    <!-- Featured Products Section -->
    <section class="max-w-7xl mx-auto px-4 py-12">
      <h2 class="text-4xl font-bold mb-8 text-center">Featured Products</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Product Card 1 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
          <img
            src="https://via.placeholder.com/300x200?text=Product+1"
            alt="Product 1"
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="font-bold text-lg mb-2">Premium Headphones</h3>
            <p class="text-gray-600 text-sm mb-3">High-quality sound experience</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold text-blue-600">$79.99</span>
              <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Add to Cart
              </button>
            </div>
          </div>
        </div>

        <!-- Product Card 2 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
          <img
            src="https://via.placeholder.com/300x200?text=Product+2"
            alt="Product 2"
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="font-bold text-lg mb-2">Wireless Mouse</h3>
            <p class="text-gray-600 text-sm mb-3">Precision tracking & comfort</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold text-blue-600">$29.99</span>
              <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Add to Cart
              </button>
            </div>
          </div>
        </div>

        <!-- Product Card 3 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
          <img
            src="https://via.placeholder.com/300x200?text=Product+3"
            alt="Product 3"
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="font-bold text-lg mb-2">USB-C Cable</h3>
            <p class="text-gray-600 text-sm mb-3">Fast charging & data transfer</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold text-blue-600">$12.99</span>
              <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Add to Cart
              </button>
            </div>
          </div>
        </div>

        <!-- Product Card 4 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
          <img
            src="https://via.placeholder.com/300x200?text=Product+4"
            alt="Product 4"
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h3 class="font-bold text-lg mb-2">Phone Stand</h3>
            <p class="text-gray-600 text-sm mb-3">Adjustable & stable design</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold text-blue-600">$15.99</span>
              <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-12">
        <router-link
          to="/products"
          class="inline-block bg-gray-900 text-white font-bold py-3 px-8 rounded-lg hover:bg-gray-800 transition"
        >
          View All Products
        </router-link>
      </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-gray-900 text-white py-12 px-4 mt-12">
      <div class="max-w-2xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
        <p class="mb-6 text-gray-300">Get the latest deals and updates delivered to your inbox</p>
        <div class="flex gap-2">
          <input
            type="email"
            placeholder="Enter your email"
            class="flex-1 px-4 py-3 rounded-lg text-gray-900"
          />
          <button
            class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-bold"
          >
            Subscribe
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import welcomeImg from '@/assets/images/welcome.png'
export default {
  name: 'CustomerHome',
  data() {
    return {
      welcomeImg, // Add this!
    }
  },
}
</script>
