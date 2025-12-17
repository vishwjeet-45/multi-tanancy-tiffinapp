<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  routeName: String,
  modelValue: String,
})

const emit = defineEmits(['update:modelValue'])

const search = ref(props.modelValue || '')

let timeout = null

watch(search, (value) => {
  emit('update:modelValue', value)

  clearTimeout(timeout)

  timeout = setTimeout(() => {
    router.get(
      route(props.routeName),
      { search: value, page: 1 },
      {
        preserveState: true,
        replace: true,
      }
    )
  }, 400)
})
</script>

<template>
  <input
    v-model="search"
    type="text"
    placeholder="Search..."
    class="border rounded px-3 py-2 w-64 focus:outline-none focus:ring"
  />
</template>
