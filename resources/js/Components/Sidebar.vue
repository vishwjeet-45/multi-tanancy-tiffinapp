<template>

    <div class="overflow-y-auto h-full px-3 py-4">
        <div class="flex shrink-0 items-center">
            <Link :href="route('dashboard')" class="pl-5 ml-1 mb-3">
                <ApplicationLogo
                    class="block h-9 w-auto fill-current text-gray-800"
                />
            </Link>
        </div>
      <nav>
        <ul class="space-y-1">

          <template v-for="(menu, index) in localMenuItems" :key="index">

            <li>
              <template v-if="menu.submenu && menu.submenu.length">

                <button
                  @click="toggle(index)"
                  class="w-full flex items-center justify-between gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition"
                >
                  <div class="flex items-center gap-3">
                    <span v-html="menu.icon" class="text-xl text-blue-500"></span>
                    <span class="text-left font-medium">{{ menu.title }}</span>
                  </div>

                  <svg
                    class="w-4 h-4 text-gray-400 transform transition duration-300"
                    :class="{ 'rotate-90': menu.open }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5l7 7-7 7" />
                  </svg>
                </button>

                <ul
                  class="ml-5 mt-1 space-y-1 overflow-hidden transition-all duration-300 ease-in-out"
                  :style="{ maxHeight: menu.open ? (menu.submenu.length * 50) + 'px' : '0px' }"
                >
                  <li
                    v-for="(sub, sIdx) in menu.submenu"
                    :key="sIdx"
                  >
                    <Link
                      :href="sub.link"
                      class="flex items-center gap-3 pl-6 pr-4 py-2 rounded-lg text-sm text-gray-600 transition"
                      :class="sub.is_active
                        ? 'bg-[rgb(223,247,182)] text-blue-600 font-semibold'
                        : 'hover:bg-gray-50 hover:text-gray-800'"
                    >
                      <i class="ri-arrow-right-s-line text-lg"></i>
                      {{ sub.title }}
                    </Link>
                  </li>
                </ul>

              </template>

              <template v-else>
                <Link
                  :href="menu.link"
                  class="w-full flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg transition"
                  :class="menu.is_active
                    ? 'bg-[rgb(208,243,149)] text-dark font-semibold shadow-md'
                    : 'hover:bg-[rgb(248,255,236)] font-medium'"
                >
                  <span v-html="menu.icon"
                    class="text-xl"
                    :class="menu.is_active ? 'text-white' : 'text-blue-500'">
                  </span>
                  <span v-show="props.isCollapsed">{{ menu.title }}</span>
                </Link>
              </template>

            </li>

          </template>

        </ul>
      </nav>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import ApplicationLogo from '@/Components/ApplicationLogo.vue';



const props = defineProps({
  menuItems: {
    type: Array,
    required: true,
    default: () => [],
  },
    isCollapsed: {
    type: Boolean,
    default: false
  }
});

const localMenuItems = ref(JSON.parse(JSON.stringify(props.menuItems)));


watch(
  () => props.menuItems,
  (newItems) => {
    const newLocal = JSON.parse(JSON.stringify(newItems)).map(newItem => {
        const existingItem = localMenuItems.value.find(
            oldItem => oldItem.title === newItem.title
        );
        return {
            ...newItem,
            open: existingItem ? existingItem.open : false
        };
    });
    localMenuItems.value = newLocal;
  },
  { deep: true }
);

const toggle = (index) => {
  localMenuItems.value[index].open = !localMenuItems.value[index].open;
};
computed(() => {
    localMenuItems.value.forEach((menu, index) => {
        if (menu.submenu && menu.submenu.length) {
            const isActive = menu.submenu.some(sub => sub.is_active);
            if (isActive && !menu.open) {
                localMenuItems.value[index].open = true;
            }
        }
    });
});

const isCollapsed = ref(false);

function handleSidebarToggle(value) {
    console.log(value);
    isCollapsed.value = value;
}
</script>


