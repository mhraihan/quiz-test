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
        <div class="flex justify-center md:block">
          <FormCheckRadio
            v-model="userSwitchVal"
            name="notifications-switch"
            type="switch"
            label="Notifications"
            :input-value="true"
          />
        </div>
        <h1 class="text-2xl">
          Howdy, <b>{{ userName }}</b
          >!
        </h1>
        <p>Last login <b>12 mins ago</b> from <b>127.0.0.1</b></p>
        <div class="flex justify-center md:block">
          <PillTag label="Verified" color="info" :icon="mdiCheckDecagram" />
        </div>
      </div>
    </BaseLevel>
  </CardBox>
</template>

