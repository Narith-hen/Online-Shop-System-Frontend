<template>
  <Teleport to="body">
    <transition name="modal">
      <div v-if="visible" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" @click.self="close">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center animate-scale-in">
          <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-5">
            <i class="fas fa-check-circle text-4xl text-green-500"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Added to Cart!</h3>
          <p class="text-gray-600 mb-6">{{ message }}</p>
          <button
            @click="close"
            class="w-full bg-blue-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-600 transition"
          >
            OK
          </button>
        </div>
      </div>
    </transition>
  </Teleport>
</template>

<script>
export default {
  name: 'SuccessModal',
  data() {
    return {
      visible: false,
      message: '',
      resolvePromise: null,
    }
  },
  methods: {
    show(msg) {
      this.message = msg
      this.visible = true
      return new Promise((resolve) => {
        this.resolvePromise = resolve
      })
    },
    close() {
      this.visible = false
      if (this.resolvePromise) {
        this.resolvePromise()
        this.resolvePromise = null
      }
    },
  },
}
</script>

<style scoped>
.animate-scale-in {
  animation: scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}
.modal-enter-active { transition: all 0.3s ease; }
.modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from { opacity: 0; }
.modal-leave-to { opacity: 0; }
.modal-enter-from .relative { transform: scale(0.9); }
</style>
