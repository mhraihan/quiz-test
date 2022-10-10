<script setup>
import { containerMaxW } from "@/config.js";
import BaseLevel from "@/Components/BaseLevel.vue";

const year = new Date().getFullYear();
</script>

<template>
  <footer class="px-6 py-2" :class="containerMaxW">
    <BaseLevel>
      <div class="text-center md:text-left">
        <b
          >&copy;{{ year }},
          <a href="/" target="_blank">Quiz App</a>.</b
        >
        <slot />
      </div>
    </BaseLevel>
  </footer>
</template>
