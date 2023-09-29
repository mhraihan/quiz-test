<script setup>
import CardBox from "@/Components/CardBox.vue";

const props = defineProps({
    image: String,
    title: String,
})

</script>

<template>
    <cardBox v-if="props.image" class="my-5">
        <h2 class="my-4 font-bold text-2xl">Question Image </h2>
        <img :src="props.image" loading="lazy" :alt="props.title" class="">
    </cardBox>
</template>
