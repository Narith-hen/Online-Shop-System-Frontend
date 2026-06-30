<template>
  <div>
    <section class="relative text-white py-24 px-4" style="background-image: url('https://cdn.analyticsvidhya.com/wp-content/uploads/2024/03/customer-serive.webp'); background-size: cover; background-position: center;">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
      <div class="max-w-7xl mx-auto text-center relative animate-fade-in-down">
        <h1 class="text-5xl font-extrabold mb-3 drop-shadow-lg">Contact Us</h1>
        <p class="text-xl text-white/80 font-light max-w-2xl mx-auto">We'd love to hear from you. Get in touch with us today!</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-16">

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
        <div v-for="(card, i) in contactCards" :key="i"
          class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 animate-fade-in-up text-center group"
          :style="{ animationDelay: `${i * 100}ms` }">
          <div class="w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform"
            :class="card.bg">
            <i :class="card.icon"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">{{ card.title }}</h3>
          <p class="text-gray-500 text-sm mb-3">{{ card.subtitle }}</p>
          <a v-if="card.href" :href="card.href" class="text-blue-500 font-semibold hover:text-blue-600 transition text-sm">
            {{ card.value }}
          </a>
          <p v-else class="text-blue-500 font-semibold text-sm leading-relaxed" v-html="card.value"></p>
          <p class="text-gray-400 text-xs mt-2">{{ card.note }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">

        <div class="lg:col-span-3 animate-fade-in-left">
          <span class="text-sm font-semibold text-blue-500 uppercase tracking-wider">Get In Touch</span>
          <h2 class="text-3xl font-bold text-gray-900 mt-2 mb-8">Send us a Message</h2>

          <form @submit.prevent="submitForm" class="space-y-5" novalidate>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                <input type="text" v-model="form.firstName" placeholder="John"
                  class="form-input" :class="{ error: errors.firstName }" required />
                <p v-if="errors.firstName" class="text-red-500 text-xs mt-1">{{ errors.firstName }}</p>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                <input type="text" v-model="form.lastName" placeholder="Smith"
                  class="form-input" :class="{ error: errors.lastName }" required />
                <p v-if="errors.lastName" class="text-red-500 text-xs mt-1">{{ errors.lastName }}</p>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
              <input type="email" v-model="form.email" placeholder="john@example.com"
                class="form-input" :class="{ error: errors.email }" required />
              <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone (Optional)</label>
              <input type="tel" v-model="form.phone" placeholder="+1-800-123-4567"
                class="form-input" />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
              <select v-model="form.subject"
                class="form-select" :class="{ error: errors.subject }" required>
                <option value="">Select a subject</option>
                <option value="order">Order Inquiry</option>
                <option value="shipping">Shipping Question</option>
                <option value="return">Return / Exchange</option>
                <option value="feedback">Feedback / Suggestion</option>
                <option value="other">Other</option>
              </select>
              <p v-if="errors.subject" class="text-red-500 text-xs mt-1">{{ errors.subject }}</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
              <textarea v-model="form.message" placeholder="Tell us how we can help..." rows="5"
                class="form-input resize-none" :class="{ error: errors.message }" required></textarea>
              <p v-if="errors.message" class="text-red-500 text-xs mt-1">{{ errors.message }}</p>
            </div>

            <label class="flex items-start gap-3 cursor-pointer">
              <input type="checkbox" v-model="form.agree"
                class="mt-0.5 w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-400 accent-blue-500" />
              <span class="text-sm text-gray-600">
                I agree to the
                <a href="#" class="text-blue-500 hover:text-blue-600 font-semibold">terms of service</a>
                and
                <a href="#" class="text-blue-500 hover:text-blue-600 font-semibold">privacy policy</a>.
                <span class="text-red-500">*</span>
              </span>
            </label>
            <p v-if="errors.agree" class="text-red-500 text-xs">{{ errors.agree }}</p>

            <button type="submit" :disabled="submitting"
              class="btn-primary w-full text-base py-3.5">
              <i v-if="submitting" class="fas fa-spinner fa-spin mr-2"></i>
              <i v-else class="fas fa-paper-plane mr-2"></i>
              {{ submitting ? 'Sending...' : 'Send Message' }}
            </button>
          </form>
        </div>

        <div class="lg:col-span-2 animate-fade-in-right">
          <div class="bg-gradient-to-br from-gray-50 to-blue-100 rounded-2xl p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6"><i class="fas fa-clock text-blue-500 mr-2"></i> Business Hours</h3>
            <div class="space-y-4">
              <div v-for="(hour, i) in businessHours" :key="i"
                class="flex items-center justify-between py-2"
                :class="i < businessHours.length - 1 ? 'border-b border-gray-200' : ''">
                <span class="font-semibold text-gray-900">{{ hour.day }}</span>
                <span :class="hour.closed ? 'text-red-500 font-semibold' : 'text-gray-600'">{{ hour.time }}</span>
              </div>
            </div>
          </div>

          <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-6"><i class="fas fa-share-nodes text-blue-500 mr-2"></i> Follow Us</h3>
            <div class="flex gap-3">
              <a v-for="(social, i) in socialLinks" :key="i" :href="social.url" target="_blank" rel="noopener"
                class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                :style="{ background: social.color }"
                :aria-label="social.name">
                <i :class="social.icon"></i>
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>

    <Toast ref="toastRef" />
  </div>
</template>

<script>
import Toast from '@/components/Toast.vue'

export default {
  name: 'ContactPage',
  components: { Toast },
  data() {
    return {
      submitting: false,
      form: { firstName: '', lastName: '', email: '', phone: '', subject: '', message: '', agree: false },
      errors: {},
      contactCards: [
        { icon: 'fas fa-envelope text-blue-500 text-xl', bg: 'bg-blue-200', title: 'Email', subtitle: 'Get in touch via email', value: 'support@onlineshop.com', href: 'mailto:support@onlineshop.com', note: 'Response time: within 24 hours' },
        { icon: 'fas fa-phone text-emerald-600 text-xl', bg: 'bg-emerald-100', title: 'Phone', subtitle: 'Call us during business hours', value: '+1-800-123-4567', href: 'tel:+18001234567', note: 'Mon-Fri: 9 AM - 6 PM EST' },
        { icon: 'fas fa-location-dot text-purple-600 text-xl', bg: 'bg-purple-100', title: 'Address', subtitle: 'Visit our office', value: '123 Shop Street<br/>New York, NY 10001<br/>United States', note: 'Headquarters' },
      ],
      businessHours: [
        { day: 'Monday - Friday', time: '9:00 AM - 6:00 PM EST' },
        { day: 'Saturday', time: '10:00 AM - 4:00 PM EST' },
        { day: 'Sunday', time: 'Closed', closed: true },
      ],
      socialLinks: [
        { name: 'Facebook', icon: 'fab fa-facebook-f', color: '#1877F2', url: '#' },
        { name: 'Twitter', icon: 'fab fa-x-twitter', color: '#000000', url: '#' },
        { name: 'Instagram', icon: 'fab fa-instagram', color: '#E4405F', url: '#' },
        { name: 'YouTube', icon: 'fab fa-youtube', color: '#FF0000', url: '#' },
        { name: 'LinkedIn', icon: 'fab fa-linkedin-in', color: '#0A66C2', url: '#' },
      ],
    }
  },
  methods: {
    validate() {
      const e = {}
      if (!this.form.firstName.trim()) e.firstName = 'First name is required.'
      if (!this.form.lastName.trim()) e.lastName = 'Last name is required.'
      if (!this.form.email.trim()) e.email = 'Email is required.'
      else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) e.email = 'Please enter a valid email address.'
      if (!this.form.subject) e.subject = 'Please select a subject.'
      if (!this.form.message.trim()) e.message = 'Message is required.'
      else if (this.form.message.trim().length < 10) e.message = 'Message must be at least 10 characters.'
      if (!this.form.agree) e.agree = 'You must agree to the terms and privacy policy.'
      this.errors = e
      return Object.keys(e).length === 0
    },
    async submitForm() {
      if (!this.validate()) return
      this.submitting = true
      try {
        await new Promise(resolve => setTimeout(resolve, 1200))
        this.$refs.toastRef?.show({ type: 'success', title: 'Message Sent!', message: 'Thank you for contacting us! We will get back to you within 24 hours.' })
        this.form = { firstName: '', lastName: '', email: '', phone: '', subject: '', message: '', agree: false }
        this.errors = {}
      } catch {
        this.$refs.toastRef?.show({ type: 'error', title: 'Error', message: 'Failed to send message. Please try again later.' })
      } finally { this.submitting = false }
    },
  },
}
</script>
