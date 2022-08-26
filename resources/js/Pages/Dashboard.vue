<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Options from "@/Components/Quizs/Options.vue";
import Quiz from "@/Components/Quizs/Quiz.vue";
import Retake from "@/Components/Quizs/Retake.vue";
import { store } from "@/store.js";
</script>

<template>
  <Head title="Quiz App" />

  <BreezeAuthenticatedLayout>
    <!-- <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Dashboard
      </h2>
    </template> -->

    <div class="py-12">
      <div class="text-gray-700">
        <Options v-if="store.step == 0"></Options>
        <Quiz v-if="store.step == 1"></Quiz>
        <Retake v-if="store.step == 2"></Retake>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
