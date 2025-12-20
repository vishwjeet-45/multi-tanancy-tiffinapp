<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';


const props = defineProps({
  theme: Object
});
const showingNavigationDropdown = ref(false);

const page = usePage();

const isCollapsed = ref(false);

const emit = defineEmits(['toggle']);

function toggleSidebar() {
    isCollapsed.value = !isCollapsed.value;
    emit('toggle', isCollapsed.value);
}
</script>
<template>
    <nav class=" sticky top-0 z-10" id="card"
    :style="{
      backgroundColor: theme.sidebarBg,
      color: theme.sidebarText
    }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">

                    <button
                        @click="toggleSidebar"
                        class="p-2 rounded-md"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 5.25h16.5M3.75 12h16.5m-16.5 6.75h16.5" />
                        </svg>
                    </button>
                </div>

                <div class="hidden sm:ms-6 sm:flex sm:items-center">
                    <div class="relative ms-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent  px-3 py-2 text-sm font-medium leading-4  transition duration-150 ease-in-out  focus:outline-none"
                                    >
                                        {{ page.props.auth.user?.name }}

                                        <svg
                                            class="-me-0.5 ms-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('tenant.logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <div class="-me-2 flex items-center sm:hidden">
                    <button
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                    >
                        <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div
            :class="{
                block: showingNavigationDropdown,
                hidden: !showingNavigationDropdown,
            }"
            class="sm:hidden"
        >
            <div class="space-y-1 pb-3 pt-2">
                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Dashboard
                </ResponsiveNavLink>
            </div>

            <div class="border-t border-gray-200 pb-1 pt-4">
                <div class="px-4">
                    <div class="text-base font-medium text-gray-800">
                        {{ page.props.auth.user?.name }}
                    </div>
                    <div class="text-sm font-medium text-gray-500">
                        {{ page.props.auth.user?.email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('profile.edit')">
                        Profile
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        Log Out
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
