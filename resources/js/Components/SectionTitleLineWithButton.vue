<script setup>
import { useSlots, computed } from "vue";
import BaseIcon from "@/Components/BaseIcon.vue";
import IconRounded from "@/Components/IconRounded.vue";
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    icon: {
        type: String,
        default: null,
    },
    link: {
        type: String,
        default: null,
    },
    title: {
        type: String,
        required: true,
    },
    main: Boolean,
});

const hasSlot = computed(() => useSlots().default);
</script>

<template>
    <section
        :class="{ 'pt-6': !main }"
        class="flex items-center justify-between mb-6"
    >
        <div   class="flex items-center justify-start">
            <Link  v-if="icon && main && link" :href="route(link)">
                <IconRounded
                    v-if="icon && main"
                    :icon="icon"
                    color="light"
                    class="mr-3"
                    bg
                />
            </Link>
            <IconRounded
                v-else-if="icon && main"
                :icon="icon"
                color="light"
                class="mr-3"
                bg
            />
            <BaseIcon v-else-if="icon" :path="icon" class="mr-2" size="20" />
            <h1 :class="main ? 'text-3xl' : 'text-2xl'" class="leading-tight">
                {{ title }}
            </h1>
        </div>
        <slot v-if="hasSlot" />
    </section>
</template>
