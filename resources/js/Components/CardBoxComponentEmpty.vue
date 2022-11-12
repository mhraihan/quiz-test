<script setup>
import {computed } from 'vue';

const props = defineProps({
    text: {
        type: String,
        default: "Nothing's here…"
    }
});
const text = computed(() => props.text || "Nothing's here…" );
</script>
<template>
    <div class="text-center py-24 text-gray-500 dark:text-slate-400">
        <p>{{ text }}</p>
    </div>
</template>
