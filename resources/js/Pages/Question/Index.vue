<script setup>
import Default from "@/Pages/Question/Default.vue";
const props = defineProps({
    Questions: Object,
    title: String
});
</script>

<template>
    <Default :Questions="props.Questions" :title="props.title"/>
</template>
