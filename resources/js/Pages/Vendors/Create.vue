<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  domain: '',
});

const submit = () => {
  form.post('/vendors');
};
</script>

<template>
  <div class="max-w-lg mx-auto mt-10 p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">Create Vendor</h1>

    <form @submit.prevent="submit">
      <!-- Vendor Name -->
      <div class="mb-4">
        <label class="block mb-1 font-medium">Vendor Name</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full border rounded px-3 py-2"
          placeholder="Vishwjeet Tiffin"
        />
        <div v-if="form.errors.name" class="text-red-600 text-sm">
          {{ form.errors.name }}
        </div>
      </div>

      <!-- Domain -->
      <div class="mb-4">
        <label class="block mb-1 font-medium">Subdomain</label>
        <div class="flex">
          <input
            v-model="form.domain"
            type="text"
            class="w-full border rounded-l px-3 py-2"
            placeholder="vishwjeet"
          />
          <span class="bg-gray-200 px-3 py-2 rounded-r">
            .tiffinapp.test
          </span>
        </div>
        <div v-if="form.errors.domain" class="text-red-600 text-sm">
          {{ form.errors.domain }}
        </div>
      </div>

      <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded"
        :disabled="form.processing"
      >
        Create Vendor
      </button>

      <div v-if="$page.props.flash?.success" class="mt-3 text-green-600">
        {{ $page.props.flash.success }}
      </div>
    </form>
  </div>
</template>
