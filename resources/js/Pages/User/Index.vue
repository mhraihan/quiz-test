<script setup>
import Default from "@/Pages/User/Default.vue";
const props = defineProps({
    Users: Object,
    title: String
});
</script>

<template>
    <Default :Users="props.Users" :title="props.title"/>
</template>
