<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="text-center animate-fade-in">

      <div class="flex items-center justify-center gap-2 mb-8 animate-fade-in-down">
        <h1 class="text-7xl font-black text-red-500">4</h1>
        <img src="/images/logo.png" alt="Online Shop" class="w-32 animate-scale-in" />
        <h1 class="text-7xl font-black text-red-500">4</h1>
      </div>

      <h2 class="text-4xl font-bold text-gray-900 mb-3 animate-fade-in-up">Oops! You're lost.</h2>

      <p class="text-lg text-gray-600 mb-12 animate-fade-in-up" style="animation-delay: 120ms">The page you are looking for was not found.</p>

      <router-link
        to="/"
        class="inline-block bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-12 rounded-full transition duration-300"
      >
        Back to Home
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound',
  methods: {
    goBack() {
      this.$router.go(-1)
    },
  },
}
</script>
