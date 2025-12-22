/* Pages/Users/Index.vue */
<template>
    <AppLayout>
        <template #title>Users</template>
        <Head title="Users" />
        <div class="flex justify-end mb-4">
            <Link
                :href="route('tenant.users.create')"
                class="btn-outline-primary px-4 py-2 rounded"
            >
                Add User
            </Link>
        </div>

        <div class="w-full card rounded shadow p-3">
            <div class="card-body">
                <div class="flex justify-end mb-4">
                    <Search route-name="tenant.users.index" v-model="search" />
                </div>
                <table class="w-full card cardTX rounded shadow">
                    <thead>
                        <tr>
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Email</th>
                            <th class="p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="user in users.data"
                            :key="user.id"
                            class="border-t"
                        >
                            <td class="p-2 text-gray-700">{{ user.name }}</td>
                            <td class="p-2 text-gray-700">{{ user.email }}</td>
                            <td class="p-2 text-center">
                                <button
                                    @click="openEditModal(user)"
                                    class="btn-primary hover:bg-yellow-600 text-white px-3 py-1 rounded"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="deleteUser(user.id)"
                                    class="btn-danger text-white px-3 py-1 rounded"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :links="users.links" />
            </div>
        </div>
    </AppLayout>

    <div
        v-if="showEditModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="bg-white w-96 rounded shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Edit User</h2>

            <div class="mb-3">
                <input
                    v-model="editForm.name"
                    type="text"
                    class="w-full border rounded px-3 py-2"
                    @input="editForm.clearErrors('name')"
                />

                <p v-if="editForm.errors.name" class="text-red-500 text-sm">
                    {{ editForm.errors.name }}
                </p>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Email</label>
                <input
                    v-model="editForm.email"
                    type="email"
                    class="w-full border rounded px-3 py-2"
                    @input="editForm.clearErrors('email')"
                />
                <p v-if="editForm.errors.email" class="text-red-500 text-sm">
                    {{ editForm.errors.email }}
                </p>
            </div>

            <div class="flex justify-end gap-2">
                <button
                    @click="closeEditModal"
                    class="px-4 py-2 bg-gray-300 rounded"
                >
                    Cancel
                </button>

                <button
                    @click="updateUser"
                    class="px-4 py-2 bg-blue-600 text-white rounded"
                >
                    Save
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router, usePage, Head, useForm } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import Search from "@/Components/Search.vue";
import { ref, watch } from "vue";
import Swal from "sweetalert2";

const page = usePage();
const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const showEditModal = ref(false);

// const editForm = ref({
//     id: null,
//     name: "",
//     email: "",
// });

const editForm = useForm({
    id: null,
    name: "",
    email: "",
});

const openEditModal = (user) => {
    editForm.id = user.id;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.clearErrors();
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
};

const updateUser = () => {
    editForm.put(route("tenant.users.update", editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire("Updated!", "User Updated successfully", "success");
            showEditModal.value = false;
            editForm.reset();
        },
    });
};

const deleteUser = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("tenant.users.destroy", id), {
                preserveScroll: true,

                onSuccess: () => {
                    const flash = usePage().props.flash;

                    if (flash.success) {
                        Swal.fire("Deleted!", flash.success, "success");
                    }

                    if (flash.error) {
                        Swal.fire("Error!", flash.error, "error");
                    }
                },
            });
        }
    });
};
</script>
