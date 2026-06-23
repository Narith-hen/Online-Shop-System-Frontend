<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">Our Products</h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      
      <!-- Sidebar Filters -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6">
          
          <!-- Search -->
          <div class="mb-6">
            <h3 class="font-bold text-lg mb-3">Search</h3>
            <input 
              type="text" 
              placeholder="Search products..." 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
            />
          </div>

          <!-- Categories -->
          <div class="mb-6">
            <h3 class="font-bold text-lg mb-3">Categories</h3>
            <div class="space-y-2">
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>Electronics</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>Accessories</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>Gadgets</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>Audio</span>
              </label>
            </div>
          </div>

          <!-- Price Range -->
          <div class="mb-6">
            <h3 class="font-bold text-lg mb-3">Price Range</h3>
            <div class="space-y-2">
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>Under $20</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>$20 - $50</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>$50 - $100</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>Over $100</span>
              </label>
            </div>
          </div>

          <!-- Rating -->
          <div>
            <h3 class="font-bold text-lg mb-3">Rating</h3>
            <div class="space-y-2">
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>⭐⭐⭐⭐⭐ 5 Stars</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>⭐⭐⭐⭐ 4+ Stars</span>
              </label>
              <label class="flex items-center">
                <input type="checkbox" class="mr-2" />
                <span>⭐⭐⭐ 3+ Stars</span>
              </label>
            </div>
          </div>

        </div>
      </div>

      <!-- Products Grid -->
      <div class="lg:col-span-3">
        
        <!-- Sort Options -->
        <div class="mb-6 flex justify-between items-center">
          <p class="text-gray-600">Showing 12 products</p>
          <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
            <option>Sort by: Newest</option>
            <option>Sort by: Price (Low to High)</option>
            <option>Sort by: Price (High to Low)</option>
            <option>Sort by: Rating</option>
            <option>Sort by: Best Sellers</option>
          </select>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          
          <!-- Product Item -->
          <div v-for="product in 9" :key="product" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="relative">
              <img src="https://via.placeholder.com/300x200" alt="Product" class="w-full h-48 object-cover" />
              <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded text-sm font-bold">
                Sale
              </div>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-lg mb-2">Premium Product {{ product }}</h3>
              <div class="flex items-center gap-1 mb-2">
                <span class="text-yellow-400">⭐⭐⭐⭐⭐</span>
                <span class="text-gray-600 text-sm">(128 reviews)</span>
              </div>
              <p class="text-gray-600 text-sm mb-4">High quality product with great features</p>
              <div class="flex justify-between items-center">
                <div>
                  <span class="text-2xl font-bold text-blue-600">$49.99</span>
                  <span class="text-lg text-gray-400 line-through ml-2">$79.99</span>
                </div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                  Add
                </button>
              </div>
            </div>
          </div>

        </div>

        <!-- Pagination -->
        <div class="flex justify-center gap-2 mt-12">
          <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">← Previous</button>
          <button class="px-4 py-2 bg-blue-600 text-white rounded">1</button>
          <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">2</button>
          <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">3</button>
          <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Next →</button>
        </div>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'Products'
}
</script>