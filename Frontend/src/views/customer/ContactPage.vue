<template>
  <div>
    <!-- Header -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 px-4">
      <div class="max-w-7xl mx-auto text-center">
        <h1 class="text-5xl font-bold mb-4">Contact Us</h1>
        <p class="text-xl">We'd love to hear from you. Get in touch with us today!</p>
      </div>
    </section>

    <!-- Contact Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
      
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Contact Information Cards -->
        <div class="bg-white rounded-lg shadow-md p-8">
          <div class="text-4xl mb-4">📧</div>
          <h3 class="text-xl font-bold mb-2">Email</h3>
          <p class="text-gray-600 mb-2">Get in touch via email</p>
          <a href="mailto:support@onlineshop.com" class="text-blue-600 font-semibold hover:text-blue-800">
            support@onlineshop.com
          </a>
          <p class="text-gray-500 text-sm mt-2">Response time: 24 hours</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
          <div class="text-4xl mb-4">📞</div>
          <h3 class="text-xl font-bold mb-2">Phone</h3>
          <p class="text-gray-600 mb-2">Call us during business hours</p>
          <a href="tel:+18001234567" class="text-blue-600 font-semibold hover:text-blue-800">
            +1-800-123-4567
          </a>
          <p class="text-gray-500 text-sm mt-2">Mon-Fri: 9 AM - 6 PM EST</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
          <div class="text-4xl mb-4">📍</div>
          <h3 class="text-xl font-bold mb-2">Address</h3>
          <p class="text-gray-600 mb-2">Visit our office</p>
          <p class="text-blue-600 font-semibold">
            123 Shop Street<br/>
            New York, NY 10001<br/>
            United States
          </p>
        </div>

      </div>

      <!-- Contact Form -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        
        <!-- Form -->
        <div>
          <h2 class="text-3xl font-bold mb-8">Send us a Message</h2>
          <form @submit.prevent="submitForm" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                <input 
                  type="text" 
                  v-model="form.firstName"
                  placeholder="John" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                <input 
                  type="text" 
                  v-model="form.lastName"
                  placeholder="Smith" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                  required
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
              <input 
                type="email" 
                v-model="form.email"
                placeholder="john@example.com" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                required
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Phone (Optional)</label>
              <input 
                type="tel" 
                v-model="form.phone"
                placeholder="+1-800-123-4567" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
              <select 
                v-model="form.subject"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                required
              >
                <option value="">Select a subject</option>
                <option value="order">Order Inquiry</option>
                <option value="shipping">Shipping Question</option>
                <option value="return">Return/Exchange</option>
                <option value="feedback">Feedback</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
              <textarea 
                v-model="form.message"
                placeholder="Tell us how we can help..." 
                rows="6"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                required
              ></textarea>
            </div>

            <div class="flex items-center">
              <input type="checkbox" id="agree" class="mr-2" required />
              <label for="agree" class="text-sm text-gray-600">
                I agree to the terms and privacy policy
              </label>
            </div>

            <button 
              type="submit"
              class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition"
            >
              Send Message
            </button>

          </form>
        </div>

        <!-- Map or Additional Info -->
        <div>
          <h2 class="text-3xl font-bold mb-8">Office Location</h2>
          <img 
            src="https://via.placeholder.com/400x500?text=Map" 
            alt="Office Location Map" 
            class="w-full rounded-lg shadow-lg mb-8"
          />
          
          <h3 class="text-2xl font-bold mb-4">Business Hours</h3>
          <div class="bg-gray-50 p-6 rounded-lg">
            <div class="flex justify-between mb-2">
              <span class="font-semibold">Monday - Friday</span>
              <span class="text-gray-600">9:00 AM - 6:00 PM EST</span>
            </div>
            <div class="flex justify-between mb-2">
              <span class="font-semibold">Saturday</span>
              <span class="text-gray-600">10:00 AM - 4:00 PM EST</span>
            </div>
            <div class="flex justify-between">
              <span class="font-semibold">Sunday</span>
              <span class="text-gray-600">Closed</span>
            </div>
          </div>

          <h3 class="text-2xl font-bold mt-8 mb-4">Follow Us</h3>
          <div class="flex gap-4">
            <a href="#" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition">
              f
            </a>
            <a href="#" class="w-12 h-12 bg-blue-400 text-white rounded-full flex items-center justify-center hover:bg-blue-500 transition">
              𝕏
            </a>
            <a href="#" class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full flex items-center justify-center hover:opacity-90 transition">
              📷
            </a>
            <a href="#" class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition">
              ▶
            </a>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'Contact',
  data() {
    return {
      form: {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        subject: '',
        message: ''
      }
    }
  },
  methods: {
    submitForm() {
      alert('Thank you for contacting us! We will get back to you soon.');
      // Reset form
      this.form = {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        subject: '',
        message: ''
      }
    }
  }
}
</script>