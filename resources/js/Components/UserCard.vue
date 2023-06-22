<script setup>
import { computed, ref } from "vue";

import { usePage } from "@inertiajs/inertia-vue3";
import UserAvatar from "@/Components/UserAvatar.vue";
import { mdiCheckDecagram } from "@mdi/js";
import BaseLevel from "@/Components/BaseLevel.vue";

import CardBox from "@/Components/CardBox.vue";
import FormCheckRadio from "@/Components/FormCheckRadio.vue";
import PillTag from "@/Components/PillTag.vue";

const userSwitchVal = ref(false);
const userName = computed(() => usePage().props.value.auth.user.name);
</script>

<template>
  <CardBox>
    <BaseLevel type="justify-around lg:justify-center">
      <UserAvatar :username="userName" api="initials" class="lg:mx-12" />
      <div class="space-y-3 text-center md:text-left lg:mx-12">

        <h1 class="text-2xl">
          Howdy, <b>{{ userName }}</b
          >!
        </h1>
      </div>
    </BaseLevel>
  </CardBox>
</template>

