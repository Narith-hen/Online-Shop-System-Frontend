<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
      <!-- 404 Illustration -->
      <div class="mb-8">
        <h1 class="text-[12rem] font-black text-white leading-none tracking-tighter opacity-90">
          404
        </h1>
        <p class="text-4xl font-semibold text-white mt-[-20px]">
          Oops!
        </p>
      </div>

      <!-- Message -->
      <div class="mb-10">
        <p class="text-xl text-white/90 font-medium">
          The page you're looking for doesn't exist or has been moved.
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <router-link
          to="/"
          class="px-8 py-4 bg-white text-indigo-700 font-semibold rounded-2xl hover:bg-indigo-50 transition-all duration-300 flex items-center justify-center gap-2 text-lg"
        >
          <span>←</span>
          Back to Home
        </router-link>

        <button
          @click="goBack"
          class="px-8 py-4 border-2 border-white/80 text-white font-semibold rounded-2xl hover:bg-white/10 transition-all duration-300 text-lg"
        >
          Go Back
        </button>
      </div>

      <!-- Optional extra help -->
      <p class="mt-12 text-white/60 text-sm">
        Need help? Contact support
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound',
  methods: {
    goBack() {
      this.$router.go(-1)
    }
  }
}
</script>