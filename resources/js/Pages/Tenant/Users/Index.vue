/* Pages/Users/Index.vue */
<template>
<AppLayout>
<template #title>Users</template>


<div class="flex justify-end mb-4">
<Link :href="route('tenant.users.create')" class="bg-blue-600 text-white px-4 py-2 rounded">
    Add User
</Link>

</div>

<div class="flex justify-end  mb-4">
  <Search
    route-name="tenant.users.index"
    v-model="search"
  />
</div>
<table class="w-full bg-white rounded shadow">
<thead class="bg-gray-100">
<tr>
<th class="p-2 text-left">Name</th>
<th class="p-2 text-left">Email</th>
<th class="p-2">Action</th>
</tr>
</thead>
<tbody>
<tr v-for="user in users.data" :key="user.id" class="border-t">
<td class="p-2">{{ user.name }}</td>
<td class="p-2">{{ user.email }}</td>
<td class="p-2 text-center">
<button @click="destroy(user.id)" class="text-red-600">Delete</button>
</td>
</tr>
</tbody>
</table>
<Pagination :links="users.links" />
</AppLayout>
</template>


<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
import Search from '@/Components/Search.vue'
import { ref } from 'vue'


const props = defineProps({
  users: Object,
  filters: Object,
})

const search = ref(props.filters?.search || '')

function destroy(id) {
if (confirm('Are you sure?')) {
router.delete(`/users/${id}`)
}
}
</script>
