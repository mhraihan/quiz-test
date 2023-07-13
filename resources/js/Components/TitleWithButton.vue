<script setup>
import {useSlots, computed} from "vue";
import BaseIcon from "@/Components/BaseIcon.vue";
import BaseButton from "@/Components/BaseButton.vue";

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
    label: {
        type: String,
        default: null,
    },
     routeName: {
        type: String,
        default: null,
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
        <div class="flex items-center justify-start">
            <BaseIcon v-if="icon" :path="icon" class="mr-2" size="20"/>
            <h1 :class="main ? 'text-3xl' : 'text-2xl'" class="leading-tight">
                {{ title }}
            </h1>
        </div>
        <BaseButton v-if="label && routeName" :label="label" :routeName="routeName" class="rounded" color="info"/>
        <slot v-if="hasSlot"/>
    </section>
</template>
