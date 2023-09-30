<template>
    <h1 class="mb-8 text-xl leading-tight">
        <Link class="text-indigo-400 hover:text-indigo-600" :href="url">{{ title }}</Link>
        <span class="text-indigo-400 font-medium"> / </span>
        <span v-html="removeHTMLTags(location)"></span>
    </h1>
</template>

<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import {computed} from "vue";
import {removeHTMLTags} from "@/config";

const props = defineProps({
    href: {
        type: String,
        required: true
    },
    title: String,
    location: String,
})
const url = computed(() => props.href + window.location.search.split('page=')[0].replace('prev_pages', 'page'));
</script>
