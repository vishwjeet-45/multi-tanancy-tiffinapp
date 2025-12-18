<script setup>
import { router } from '@inertiajs/vue3'

defineProps({
  links: Array,
})

function visit(url) {
  if (url) {
    router.visit(url, { preserveState: true })
  }
}
</script>

<template>
  <div class="flex justify-end gap-1 mt-6">
    <button
      v-for="(link, i) in links"
      :key="i"
      v-html="link.label"
      @click="visit(link.url)"
      :disabled="!link.url"
      class="px-3 py-1 border rounded"
      :class="{
        'bg-primary text-white': link.active,
        'text-gray-400': !link.url
      }"
    />
  </div>
</template>
