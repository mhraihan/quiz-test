<script setup>

import Register from "@/Pages/Auth/Register.vue";
import {UserEnum} from "@/config";
</script>
<template>
    <Register :Role="UserEnum.TEACHER" />
</template>
