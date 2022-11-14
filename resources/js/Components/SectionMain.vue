<script setup>
import { containerMaxW } from "@/config.js";
</script>

<template>
  <section class="px-2 py-6 sm:p-6" :class="containerMaxW">
    <slot />
  </section>
</template>
