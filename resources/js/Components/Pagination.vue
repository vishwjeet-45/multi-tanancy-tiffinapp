<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
  links: Array,
})

function go(url) {
  if (!url) return
  router.get(url, {}, { preserveState: true })
}
</script>

<template>
  <div v-if="links && links.length > 3" class="flex justify-end mt-6 gap-1">
    <template v-for="(link, index) in links" :key="index">
      <button
        v-if="link.url"
        @click="go(link.url)"
        class="px-3 py-1 border rounded"
        :class="{
          'bg-blue-600 text-white': link.active,
          'text-gray-400': !link.url
        }"
        :disabled="!link.url"
        v-html="link.label"
      />
      <span
        v-else
        class="px-3 py-1 text-gray-400"
        v-html="link.label"
      />
    </template>
  </div>
</template>
