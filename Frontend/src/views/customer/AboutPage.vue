<template>
  <div>
    <section class="relative text-white py-24 px-4" style="background-image: url('https://i.dell.com/is/image/DellContent/content/dam/documents-and-videos/dv2/pan-dell/en/product-launch/laptops-and-2n1s/dell-laptops/d14260/site-banners/14-intel/cs2404g0058-gl-cs-co-fy26q2-site-banner-dell-14-intel-d14260-plastic-laptop-1208x906.jpg?wid=480&hei=360&t=1'); background-size: cover; background-position: center;">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
      <div class="max-w-7xl mx-auto text-center relative animate-fade-in-down">
        <h1 class="text-5xl font-extrabold mb-3 drop-shadow-lg">About Online Shop</h1>
        <p class="text-xl text-white/80 font-light max-w-2xl mx-auto">Your trusted partner in online shopping — delivering quality products since 2020</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-16">

      <section class="mb-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div class="animate-fade-in-left">
            <span class="text-sm font-semibold text-blue-500 uppercase tracking-wider">Our Story</span>
            <h2 class="text-4xl font-bold text-gray-900 mt-2 mb-6">How It All Began</h2>
            <p class="text-gray-600 mb-4 leading-relaxed text-lg">
              Online Shop was founded in 2020 with a mission to bring quality products to customers worldwide.
              We started as a small team of passionate individuals who believed in the power of e-commerce to
              connect great products with people who need them.
            </p>
            <p class="text-gray-600 mb-4 leading-relaxed">
              Today, we serve thousands of satisfied customers across the globe, offering a carefully curated
              selection of products across various categories, from electronics to accessories, fashion to home goods.
            </p>
            <p class="text-gray-600 leading-relaxed">
              Our commitment to quality, affordability, and exceptional customer service remains unwavering,
              and we continue to grow and innovate every single day.
            </p>
          </div>
          <div class="animate-fade-in-right">
            <div class="bg-gradient-to-br from-blue-100 to-purple-50 rounded-2xl p-8 flex items-center justify-center">
              <div class="grid grid-cols-2 gap-4 w-full max-w-sm">
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                  <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                  </div>
                  <p class="text-3xl font-bold text-gray-900">{{ stats.customers }}</p>
                  <p class="text-sm text-gray-500">Happy Customers</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                  <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                  </div>
                  <p class="text-3xl font-bold text-gray-900">{{ stats.products }}</p>
                  <p class="text-sm text-gray-500">Products</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                  <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-truck text-emerald-600 text-xl"></i>
                  </div>
                  <p class="text-3xl font-bold text-gray-900">{{ stats.ordersDelivered }}</p>
                  <p class="text-sm text-gray-500">Orders Delivered</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                  <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-star text-amber-600 text-xl"></i>
                  </div>
                  <p class="text-3xl font-bold text-gray-900">{{ stats.rating }}</p>
                  <p class="text-sm text-gray-500">Avg. Rating</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="mb-20">
        <div class="text-center mb-12 animate-fade-in-down">
          <span class="text-sm font-semibold text-blue-500 uppercase tracking-wider">What We Stand For</span>
          <h2 class="text-4xl font-bold text-gray-900 mt-2">Our Core Values</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

          <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in-up">
            <div class="w-14 h-14 bg-blue-200 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
              <i class="fas fa-bullseye text-2xl text-blue-500"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Our Mission</h3>
            <p class="text-gray-600 leading-relaxed">
              To provide high-quality products at affordable prices while delivering exceptional customer service
              and creating a seamless, enjoyable shopping experience for everyone.
            </p>
          </div>

          <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in-up" style="animation-delay: 120ms">
            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
              <i class="fas fa-eye text-2xl text-purple-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Our Vision</h3>
            <p class="text-gray-600 leading-relaxed">
              To become the world's most trusted online marketplace where customers can find everything they need
              with confidence, ease, and a smile.
            </p>
          </div>

          <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in-up" style="animation-delay: 240ms">
            <div class="w-14 h-14 bg-pink-100 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
              <i class="fas fa-gem text-2xl text-pink-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Our Values</h3>
            <p class="text-gray-600 leading-relaxed">
              Integrity, quality, innovation, and customer satisfaction drive everything we do. We believe in
              building lasting relationships with our customers and partners.
            </p>
          </div>

        </div>
      </section>

      <section class="mb-20">
        <div class="text-center mb-12 animate-fade-in-down">
          <span class="text-sm font-semibold text-blue-500 uppercase tracking-wider">Why Choose Us</span>
          <h2 class="text-4xl font-bold text-gray-900 mt-2">What Sets Us Apart</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

          <div v-for="(benefit, i) in benefits" :key="i"
            class="flex gap-5 p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 animate-fade-in-up group"
            :style="{ animationDelay: `${i * 80}ms` }">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform"
              :class="benefit.bg">
              <i :class="benefit.icon"></i>
            </div>
            <div>
              <h3 class="text-lg font-bold text-gray-900 mb-1.5">{{ benefit.title }}</h3>
              <p class="text-gray-500 text-sm leading-relaxed">{{ benefit.desc }}</p>
            </div>
          </div>

        </div>
      </section>

      <section>
        <div class="text-center mb-12 animate-fade-in-down">
          <span class="text-sm font-semibold text-blue-500 uppercase tracking-wider">Our Team</span>
          <h2 class="text-4xl font-bold text-gray-900 mt-2">Meet The People Behind The Store</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center max-w-4xl mx-auto">

          <div v-for="(member, i) in team" :key="i"
            class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in-up text-center"
            :style="{ animationDelay: `${i * 80}ms` }">
            <div class="bg-gradient-to-br from-gray-100 to-gray-200 p-6 flex items-center justify-center">
              <img :src="member.image" :alt="member.name" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md" />
            </div>
            <div class="p-6">
              <h3 class="text-lg font-bold text-gray-900">{{ member.name }}</h3>
              <p class="text-sm font-semibold text-blue-500 mb-3">{{ member.role }}</p>
              <p class="text-sm text-gray-500 leading-relaxed">{{ member.bio }}</p>
            </div>
          </div>

        </div>
      </section>

    </div>
  </div>
</template>

<script>
import narithImg from '@/assets/images/NarithHen.png'
import sothinImg from '@/assets/images/SothinKe.jpg'
import { get } from '@/services/api'

export default {
  name: 'AboutPage',
  data() {
    return {
      stats: { products: '—', customers: '—', ordersDelivered: '—', rating: '—' },
      benefits: [
        { icon: 'fas fa-cubes text-blue-500', bg: 'bg-blue-200', title: 'Wide Selection', desc: 'Browse thousands of products across multiple categories curated just for you.' },
        { icon: 'fas fa-tags text-emerald-600', bg: 'bg-emerald-100', title: 'Competitive Prices', desc: 'Get the best deals with regular discounts and exclusive offers on your favorite items.' },
        { icon: 'fas fa-truck-fast text-purple-600', bg: 'bg-purple-100', title: 'Fast Shipping', desc: 'Quick and reliable delivery to your doorstep with real-time tracking available.' },
        { icon: 'fas fa-shield-halved text-amber-600', bg: 'bg-amber-100', title: 'Secure Checkout', desc: 'Safe and encrypted payment processing with multiple payment options supported.' },
        { icon: 'fas fa-arrow-rotate-left text-rose-600', bg: 'bg-rose-100', title: 'Easy Returns', desc: 'Hassle-free returns within 30 days. We make sure you are completely satisfied.' },
        { icon: 'fas fa-headset text-cyan-600', bg: 'bg-cyan-100', title: '24/7 Support', desc: 'Dedicated customer service team always ready to help with any questions or concerns.' },
      ],
      team: [
        { name: 'Narith HEN', role: 'CEO & Founder', bio: 'Visionary leader driving the company forward with a passion for e-commerce and innovation.', image: narithImg },
        { name: 'Sothin Ke', role: 'Head of Operations', bio: 'Ensures smooth daily operations and exceptional customer satisfaction across all channels.', image: sothinImg },
        { name: 'ReakSmey San', role: 'Tech Director', bio: 'Drives innovation and platform development with a passion for great user experiences.', image: 'https://media.licdn.com/dms/image/v2/D5603AQFRUl8al8Mj_Q/profile-displayphoto-crop_800_800/B56Z5CxkdLGkAI-/0/1779236752915?e=1784160000&v=beta&t=OKQTlZblnepM2udvmR6KR-nTVumLb1j5MHwXY9yZSHM' },
      ],
    }
  },
  async mounted() {
    try {
      const s = await get('/api/stats')
      this.stats = {
        products: s.products ?? 0,
        customers: s.customers ?? 0,
        ordersDelivered: s.orders_delivered ?? 0,
        rating: s.reviews_count > 0 ? s.rating : 'New',
      }
    } catch { /* keep placeholders */ }
  },
}
</script>
