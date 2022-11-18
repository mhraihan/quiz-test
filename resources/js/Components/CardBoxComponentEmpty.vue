<script setup>
import {computed} from 'vue';
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";

const props = defineProps({
    label: {
        type: String,
        default: 'Back'
    },
    text: {
        type: String,
        default: "Nothing's here…"
    },
    routeName: {
        type: String,
        default: null
    },
});
const text = computed(() => props.text || "Nothing's here…");
</script>
<template>
    <div class="text-center py-24 text-gray-500 dark:text-slate-400">
        <p>{{ text }}</p>
        <BaseButtons v-if="props.routeName" class="mt-5 flex justify-center">
            <BaseButton
                color="info"
                type="button"
                :label="props.label"
                :href="route(props.routeName)"
            />
        </BaseButtons>
    </div>
</template>
