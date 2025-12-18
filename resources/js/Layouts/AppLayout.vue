<template>
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"
        rel="stylesheet"
    />
    <div
        class="admin-wrapper flex min-h-screen"
        :style="{
            '--primary': hexToRgb(theme.primary),
            '--success': hexToRgb(theme.success),
            '--danger': hexToRgb(theme.danger),
            '--warning': hexToRgb(theme.warning),
            '--info': hexToRgb(theme.info),
            '--secondary': hexToRgb(theme.secondary),
            '--gray': hexToRgb(theme.gray),
            '--light': hexToRgb(theme.light),
            '--scrollbar': hexToRgb(theme.scrollbar),

            backgroundColor: theme.mainBg,
            color: theme.mainText,
        }"
    >
        <aside
            :class="[
                'h-screen fixed z-20 transition-all shadow-md top-0 z-20',
                isCollapsed ? 'w-16' : 'w-64',
            ]"
            :style="{
                backgroundColor: theme.sidebarBg,
                color: theme.sidebarText,
            }"
        >
            <Sidebar
                :menuItems="menuItems"
                :isCollapsed="isCollapsed ? false : true"
                :theme="theme"
            />
        </aside>
        <div
            :class="[
                'content-area flex-1  flex flex-col',
                isCollapsed ? 'ml-16' : 'ml-64',
            ]"
        >
            <Header @toggle="handleSidebarToggle" :theme="theme" />

            <main class="flex-1 p-4 sm:p-6">
                <Notification />
                <slot />
            </main>
        </div>
    </div>

    <!-- Right Theme Panel -->
    <div
        v-if="showThemePanel"
        class="w-80 bg-white shadow-lg border-l fixed right-0 top-0 h-full p-5 z-50"
    >
        <h3 class="text-lg font-bold mb-4">Theme Settings</h3>
        <div class="space-y-4">
            <div class="space-y-4 h-[394px] overflow-y-auto flex flex-col">

                <div class="flex justify-between">
                    <label class="block mb-1">Sidebar Background</label>
                    <input type="color" v-model="theme.sidebarBg" />
                </div>
                <div class="flex justify-between">
                    <label class="block mb-1">Sidebar Text</label>
                    <input type="color" v-model="theme.sidebarText" />
                </div>

                <div class="flex justify-between">
                    <label class="block mb-1">Main Background</label>
                    <input type="color" v-model="theme.mainBg" />
                </div>

                <div class="flex justify-between">
                    <label class="block mb-1">Main Text</label>
                    <input type="color" v-model="theme.mainText" />
                </div>
                <div class="flex justify-between">
                    <label class="block mb-1">Scrollbar Color</label>
                    <input type="color" v-model="theme.scrollbar" />
                </div>

                <div class="flex justify-between">
                    <label class="block mb-1">Primary Color</label>
                    <input type="color" v-model="theme.primary" />
                </div>
                <div class="flex justify-between">
                    <label class="block mb-1">Danger Color</label>
                    <input type="color" v-model="theme.danger" />
                </div>
            </div>

            <button
                class="bg-blue-600 text-white px-4 py-2 rounded w-full"
                @click="saveTheme"
            >
                Save Theme
            </button>
            <button
                class="bg-gray-200 text-gray-800 px-4 py-2 rounded w-full"
                @click="resetTheme"
            >
                Reset Theme
            </button>
            <button
                class="bg-gray-200 text-gray-800 px-4 py-2 rounded w-full"
                @click="showThemePanel=false"
            >
                Cancel
            </button>
        </div>
    </div>
    <!-- Toggle Button -->
    <button
        class="fixed right-4 bottom-6 bg-blue-600 text-white p-3 rounded-full shadow-lg"
        @click="showThemePanel = !showThemePanel"
    >
        ⚙️
    </button>
</template>

<script setup>
import Sidebar from "@/Components/Sidebar.vue";
import Header from "@/Components/Header.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, computed, onMounted, reactive, watch } from "vue";
import Notification from "@/Components/Notification.vue";

const page = usePage();
const showThemePanel = ref(false);

const menuItems = computed(() => {
    return page.props.sidebar_menu_links || [];
});

const isCollapsed = ref(false);

function handleSidebarToggle(value) {
    isCollapsed.value = value;
}

const theme = reactive({
    sidebarBg: "#ffffff",
    sidebarText: "#504c4c",
    mainBg: "#f9fafb",
    mainText: "#111827",

    primary: "#1a9bfc",
    success: "#a5dc86",
    secondary: "#777e89",
    danger: "#fc4b6c",
    warning: "#f8bb86",
    info: "#f8bb86",
    gray: "#777e89",
    light: "#ffffff",
    scrollbar: "#1a9bfc"
});

const defaultTheme = {
    sidebarBg: "#ffffff",
    sidebarText: "#504c4c",
    mainBg: "#f4faff",
    mainText: "#111827",

    primary: "#1a9bfc",
    success: "#a5dc86",
    secondary: "#777e89",
    danger: "#fc4b6c",
    warning: "#f8bb86",
    info: "#f8bb86",
    gray: "#777e89",
    light: "#ffffff",
    scrollbar: "#1a9bfc"
};

function hexToRgb(hex) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `${r}, ${g}, ${b}`;
}
onMounted(() => {
    const savedTheme = localStorage.getItem("admin_theme");
    if (savedTheme) {
        Object.assign(theme, JSON.parse(savedTheme));
    }
});

const saveTheme = () => {
    localStorage.setItem("admin_theme", JSON.stringify(theme));
    showThemePanel.value = false;
};

const resetTheme = () => {
    if (!confirm("Reset theme to default settings?")) return;

    Object.assign(theme, defaultTheme);
    localStorage.removeItem("admin_theme");
    showThemePanel.value = false;
};
</script>
