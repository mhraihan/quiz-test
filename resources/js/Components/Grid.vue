<script setup>
import { computed, useSlots } from "vue";


const slots = useSlots();

const wrapperClass = computed(() => {
    const base = [];
    const slotsLength = slots.default().length;

    if (slotsLength > 1) {
        base.push("grid grid-cols-1 gap-3");
    }

    if (slotsLength === 2) {
        base.push("md:grid-cols-2 md:gap-6");
    }

    return base;
});
</script>

<template>
    <div class="mb-0 last:mb-0">
        <div :class="wrapperClass">
            <slot />
        </div>
    </div>
</template>
