<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none">
      <transition-group name="toast">
        <div v-for="t in toasts" :key="t.id"
          class="pointer-events-auto flex items-start gap-3 bg-white rounded-xl shadow-2xl border px-5 py-4 min-w-[320px] max-w-[420px]"
          :class="borderClass(t.type)">
          <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-lg"
            :class="iconBgClass(t.type)">
            <i :class="iconClass(t.type)"></i>
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

const borderClass = (type) => {
  const map = { new_product: 'border-l-4 border-l-blue-400', success: 'border-l-4 border-l-emerald-500', error: 'border-l-4 border-l-red-500' }
  return map[type] || 'border-l-4 border-l-purple-500'
}
const iconBgClass = (type) => {
  const map = { new_product: 'bg-blue-200 text-blue-500', success: 'bg-emerald-100 text-emerald-600', error: 'bg-red-100 text-red-600' }
  return map[type] || 'bg-purple-100 text-purple-600'
}
const iconClass = (type) => {
  const map = { new_product: 'fas fa-box', success: 'fas fa-check-circle', error: 'fas fa-exclamation-circle' }
  return map[type] || 'fas fa-bell'
}

defineExpose({ show })
</script>

<style scoped>
.toast-enter-active { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
.toast-leave-active { transition: all 0.25s ease-in; }
.toast-enter-from { opacity: 0; transform: translateX(80px) scale(0.95); }
.toast-leave-to { opacity: 0; transform: translateX(80px) scale(0.95); }
</style>
