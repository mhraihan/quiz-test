<script setup>
import { usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { mdiForwardburger, mdiBackburger, mdiMenu } from "@mdi/js";
import { ref } from "vue";
import menuAside from "@/menuAside.js";
import menuNavBar from "@/menuNavBar.js";
import { useMainStore } from "@/Stores/main.js";
import BaseIcon from "@/Components/BaseIcon.vue";
import NavBar from "@/Components/NavBar.vue";
import NavBarItemPlain from "@/Components/NavBarItemPlain.vue";
import AsideMenu from "@/Components/AsideMenu.vue";
import FooterBar from "@/Components/FooterBar.vue";

useMainStore().setUser({
    name: usePage().props.value.auth.user.name,
    email: usePage().props.value.auth.user.email,
    avatar: "https://avatars.dicebear.com/api/avataaars/example.svg?options[top][]=shortHair&options[accessoriesChance]=93",
});

const layoutAsidePadding = "xl:pl-60";

const isAsideMobileExpanded = ref(false);
const isAsideLgActive = ref(false);
Inertia.on("navigate", () => {
    isAsideMobileExpanded.value = false;
    isAsideLgActive.value = false;
});

const menuClick = (event, item) => {
    if (item.isLogout) {
        // Add:
        Inertia.post(route("logout"));
    }
};
</script>

<template>
    <div
        :class="{
            dark: false,
            'overflow-hidden lg:overflow-visible': isAsideMobileExpanded,
        }"
    >
        <div
            :class="[
                layoutAsidePadding,
                { 'ml-60 lg:ml-0': isAsideMobileExpanded },
            ]"
            class="w-screen min-h-screen bg-gray-100 pt-14 transition-position lg:w-auto dark:bg-slate-800 dark:text-slate-100"
        >
            <NavBar
                :menu="menuNavBar"
                :class="[
                    layoutAsidePadding,
                    { 'ml-60 lg:ml-0': isAsideMobileExpanded },
                ]"
                @menu-click="menuClick"
            >
                <NavBarItemPlain
                    display="flex lg:hidden"
                    @click.prevent="
                        isAsideMobileExpanded = !isAsideMobileExpanded
                    "
                >
                    <BaseIcon
                        :path="
                            isAsideMobileExpanded
                                ? mdiBackburger
                                : mdiForwardburger
                        "
                        size="24"
                    />
                </NavBarItemPlain>
                <NavBarItemPlain
                    display="hidden lg:flex xl:hidden"
                    @click.prevent="isAsideLgActive = true"
                >
                    <BaseIcon :path="mdiMenu" size="24" />
                </NavBarItemPlain>
            </NavBar>
            <AsideMenu
                :is-aside-mobile-expanded="isAsideMobileExpanded"
                :is-aside-lg-active="isAsideLgActive"
                :menu="menuAside"
                @menu-click="menuClick"
                @aside-lg-close-click="isAsideLgActive = false"
            />
            <div class="body-height">
                <slot />
            </div>
            <FooterBar></FooterBar>
        </div>
    </div>
</template>
