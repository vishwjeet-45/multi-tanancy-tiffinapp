<template>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

  <div class="admin-wrapper flex min-h-screen bg-[rgb(248,255,236)] !important">
      <aside  :class="[
                'bg-white h-screen border-r border-gray-200 fixed z-20 transition-all duration-300',
                isCollapsed ? 'w-16' : 'w-64'
            ]"
        >
            <Sidebar :menuItems="menuItems"  :isCollapsed="isCollapsed ? false : true"/>

        </aside>
    <div :class="['content-area flex-1  flex flex-col', isCollapsed ? 'ml-16' : 'ml-64']">
      <Header  @toggle="handleSidebarToggle" />

      <main class="flex-1 p-4 sm:p-6">
         <Notification />
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import Sidebar from "@/Components/Sidebar.vue";
import Header from "@/Components/Header.vue";
import { usePage } from "@inertiajs/vue3";
import { ref,computed } from 'vue';
import Notification from '@/Components/Notification.vue'

const page = usePage();

const menuItems = computed(() => {
    return page.props.sidebar_menu_links || [];
});
// Assuming `sidebar_menu_links` is an array of menu objects
const menu = page.props.sidebar_menu_links;

const isCollapsed = ref(false);

function handleSidebarToggle(value) {
    isCollapsed.value = value;
}
</script>
