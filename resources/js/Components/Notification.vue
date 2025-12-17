<script setup>
import { computed, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const visible = ref(false)

const message = computed(() => {
  return page.props.flash?.success || page.props.flash?.error
})

const type = computed(() => {
  return page.props.flash?.success ? 'success' : 'error'
})

watch(message, (val) => {
  if (val) {
    visible.value = true
    setTimeout(() => {
      visible.value = false
    }, 3000)
  }
})
</script>

<template>
  <transition name="fade">
    <div
      v-if="visible"
      class="fixed top-6 right-6 z-50 px-4 py-3 rounded shadow-lg text-white"
      :class="type === 'success' ? 'bg-green-600' : 'bg-red-600'"
    >
      <div class="flex items-center gap-3">
        <span class="font-semibold">
          {{ message }}
        </span>
        <button @click="visible = false" class="font-bold">×</button>
      </div>
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
