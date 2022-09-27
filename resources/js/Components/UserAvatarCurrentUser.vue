<script setup>
import { useMainStore } from "@/Stores/main";
import UserAvatar from "@/Components/UserAvatar.vue";

const mainStore = useMainStore();
</script>

<template>
  <UserAvatar :username="mainStore.userName" :avatar="mainStore.userAvatar">
    <slot />
  </UserAvatar>
</template>
