<template>
    <h1 class="mb-8 text-xl leading-tight">
        <Link class="text-indigo-400 hover:text-indigo-600" :href="href">{{ title }}</Link>
        <span class="text-indigo-400 font-medium"> / </span>
        {{ location }}
    </h1>
</template>

<script setup>
    import { Link } from "@inertiajs/inertia-vue3";
    defineProps({
        href: {
            type: String,
            required: true
        },
        title: String,
        location: String,
    })
</script>
