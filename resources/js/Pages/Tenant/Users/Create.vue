/* Pages/Users/Create.vue */
<template>
<AppLayout>
<template #title>Create User</template>


<form @submit.prevent="submit" class="max-w-md bg-white p-6 rounded shadow">
<div class="mb-4">
<label>Name</label>
<input v-model="form.name" class="w-full border p-2 rounded" />
<InputError class="mt-2" :message="form.errors.name" />
</div>


<div class="mb-4">
<label>Email</label>
<input v-model="form.email" type="email" class="w-full border p-2 rounded" />
<InputError class="mt-2" :message="form.errors.email" />
</div>


<div class="mb-4">
<label>Password</label>
<input v-model="form.password" type="password" class="w-full border p-2 rounded" />
<InputError class="mt-2" :message="form.errors.password" />
</div>


<button class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
</form>
</AppLayout>
</template>


<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue';


const form = useForm({
name: '',
email: '',
password: ''
})


function submit() {
form.post('/users')
}
</script>
