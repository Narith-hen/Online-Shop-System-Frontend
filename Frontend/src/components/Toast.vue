<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none">
      <transition-group name="toast">
        <div
          v-for="t in toasts"
          :key="t.id"
          class="pointer-events-auto flex items-start gap-3 bg-white rounded-xl shadow-2xl border border-gray-100 px-5 py-4 min-w-[320px] max-w-[420px] animate-slide-in"
          :class="[t.type === 'new_product' ? 'border-l-4 border-l-blue-500' : 'border-l-4 border-l-purple-500']"
        >
          <div
            class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-lg"
            :class="t.type === 'new_product' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600'"
          >
            <i :class="t.type === 'new_product' ? 'fas fa-box' : 'fas fa-bell'"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 truncate">{{ t.title }}</p>
            <p class="text-sm text-gray-500 leading-snug mt-0.5 line-clamp-2">{{ t.message }}</p>
          </div>
          <button @click="dismiss(t.id)" class="shrink-0 text-gray-400 hover:text-gray-600 transition ml-2">
            <i class="fas fa-times text-sm"></i>
          </button>
        </div>
      </transition-group>
    </div>
  </Teleport>
</template>

<script setup>
import { ref } from 'vue'

const toasts = ref([])
let nextId = 1

const show = (data) => {
  const id = nextId++
  toasts.value.push({ id, ...data })
  setTimeout(() => dismiss(id), 5000)
}

const dismiss = (id) => {
  const idx = toasts.value.findIndex(t => t.id === id)
  if (idx !== -1) toasts.value.splice(idx, 1)
}

defineExpose({ show })
</script>

<style scoped>
.animate-slide-in {
  animation: slideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes slideIn {
  from { opacity: 0; transform: translateX(80px) scale(0.95); }
  to   { opacity: 1; transform: translateX(0) scale(1); }
}
.toast-enter-active { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
.toast-leave-active { transition: all 0.25s ease-in; }
.toast-enter-from { opacity: 0; transform: translateX(80px) scale(0.95); }
.toast-leave-to { opacity: 0; transform: translateX(80px) scale(0.95); }
</style>
