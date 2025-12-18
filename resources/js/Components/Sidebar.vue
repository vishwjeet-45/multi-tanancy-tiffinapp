<template>
  <div class="space-y-2"
  :style="{
      backgroundColor: theme.sidebarBg,
      color: theme.sidebarText
    }">
    <div class="flex shrink-0 items-center shadow-md py-2">
      <Link :href="route('dashboard')" class="pl-5 ml-1 mb-3">
        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" :style="{ color: theme.sidebarText }"/>
      </Link>
    </div>

    <nav class="px-3 py-4 overflow-y-auto h-[550px]">
      <ul class="space-y-1">
        <template v-for="(menu, index) in menuItems" :key="menu.id || index">
          <li>
            <!-- Menu with submenu -->
            <template v-if="hasSubmenu(menu)">
              <button
                @click="toggleMenu(index)"
                class="w-full flex items-center justify-between gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition"
                :aria-expanded="isMenuOpen(index)"
                :aria-controls="`submenu-${index}`"
                :style="{ color: theme.sidebarText }"
              >
                <div class="flex items-center gap-3" >
                  <span v-html="menu.icon" class="text-xl text-blue-500"></span>
                  <span v-show="!isCollapsed" class="text-left font-medium">
                    {{ menu.title }}
                  </span>
                </div>

                <svg
                  v-show="!isCollapsed"
                  class="w-4 h-4 text-gray-400 transform transition duration-300"
                  :class="{ 'rotate-90': isMenuOpen(index) }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  />
                </svg>
              </button>

              <ul
                v-show="!isCollapsed"
                :id="`submenu-${index}`"
                class="ml-5 mt-1 space-y-1 overflow-hidden transition-all duration-300 ease-in-out"
                :style="submenuStyle(menu, index)"
              >
                <li v-for="(sub, sIdx) in menu.submenu" :key="sub.id || sIdx">
                  <Link
                    :href="sub.link"
                    class="flex items-center gap-3 pl-6 pr-4 py-2 rounded-lg text-sm text-gray-600 transition"
                    :class="getLinkClass(sub.is_active, true)"
                  >
                    <i class="ri-arrow-right-s-line text-lg"></i>
                    {{ sub.title }}
                  </Link>
                </li>
              </ul>
            </template>

            <!-- Menu without submenu -->
            <template v-else>
              <Link
                :href="menu.link"
                class="w-full flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg transition"
                :class="getLinkClass(menu.is_active, false)"
                :style="{ color: theme.sidebarText }"
              >
                <span
                  v-html="menu.icon"
                  class="text-xl"
                  :class="menu.is_active ? 'text-white' : 'text-blue-500'"
                  :style="{ color: theme.sidebarText }"
                ></span>
                <span v-show="isCollapsed">{{ menu.title }}</span>
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
import { ref, computed, watch } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";


const props = defineProps({
  menuItems: {
    type: Array,
    required: true,
    default: () => [],
    validator: (value) => {
      return value.every(
        (item) => item.title && item.link && item.icon !== undefined
      );
    },
  },
  isCollapsed: {
    type: Boolean,
    default: false,
  },
  theme: Object
});

const emit = defineEmits(["toggle"]);

// Track which menus are open by their index
const openMenus = ref(new Set());

console.log('Menu items:',props.menuItems);
// Helper functions
const hasSubmenu = (menu) => {
  return Array.isArray(menu.submenu) && menu.submenu.length > 0;
};

const isMenuOpen = (index) => {
  return openMenus.value.has(index);
};

const toggleMenu = (index) => {
  if (openMenus.value.has(index)) {
    openMenus.value.delete(index);
  } else {
    openMenus.value.add(index);
  }
};

// Computed style for submenu animation
const submenuStyle = (menu, index) => {
  const isOpen = isMenuOpen(index);
  const itemHeight = 42; // Approximate height per submenu item
  const maxHeight = isOpen ? menu.submenu.length * itemHeight + 8 : 0;
  return {
    maxHeight: `${maxHeight}px`,
  };
};

// Computed link classes
const getLinkClass = (isActive, isSubmenu) => {
  if (isActive) {
    return isSubmenu
      ? "bg-[rgb(223,247,182)] text-blue-600 font-semibold"
      : "bg-[rgb(208,243,149)] text-dark font-semibold shadow-md";
  }
  return isSubmenu
    ? "hover:bg-gray-50 hover:text-gray-800"
    : "hover:bg-[rgb(248,255,236)] font-medium";
};

// Auto-open parent menu if any submenu item is active
const initializeOpenMenus = () => {
  props.menuItems.forEach((menu, index) => {
    if (hasSubmenu(menu)) {
      const hasActiveChild = menu.submenu.some((sub) => sub.is_active);
      if (hasActiveChild) {
        openMenus.value.add(index);
      }
    }
  });
};

// Initialize on mount and watch for changes
initializeOpenMenus();

watch(
  () => props.menuItems,
  () => {
    initializeOpenMenus();
  },
  { deep: true }
);

// Close all menus when sidebar collapses
watch(
  () => props.isCollapsed,
  (collapsed) => {
    if (collapsed) {
      openMenus.value.clear();
    } else {
      initializeOpenMenus();
    }
  }
);
</script>
