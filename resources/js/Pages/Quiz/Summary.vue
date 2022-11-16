<script setup>
import {computed, reactive} from "vue";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";

import {usePage} from "@inertiajs/inertia-vue3";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";

const profileForm = reactive({
    name: usePage().props.value.auth.user.name,
    email: usePage().props.value.auth.user.name,
});

const props = defineProps({
    result: Object
})

const score = computed(() => props.result.score);
</script>

<template>
    <SectionMain>
        <CardBox class="">
            <!-- Start of quiz box -->
            <!-- end of quiz box -->
            <section class="text-gray-600 body-font">
                <div class="shadow overflow-hidden sm:rounded-lg">
                    <div class="container px-5 py-5 mx-auto">
                        <div class="text-center mb-5 justify-center">
                            <h1 class=" sm:text-3xl text-2xl font-medium text-center title-font text-gray-900 mb-4">
                                Quiz Result
                            </h1>
                            <p>
                                <button class="bg-green-300 px-2 mx-2 hover:green-400 rounded-lg hover:underline"
                                        @click="$emit('backToQuiz')">
                                    Take another Quiz
                                </button>
                            </p>
                            <p class="text-md mt-10 mb-3"> Dear <span
                                class="font-extrabold text-blue-600 mr-2"> {{ profileForm.name }}! </span> Your latest
                                Quiz Result
                                <BaseButton
                                    routeName="results.show"
                                    :routeParams="props.result.id"
                                    type="button"
                                    class="bg-green-300 px-2 mx-2 py-0 hover:green-400 rounded-lg underline"
                                    label="See Quizzes Details"
                                />
                            </p>
                            <progress class="text-base leading-relaxed xl:w-2/4 lg:w-3/4 mx-auto"
                                      :value="props.result.score" max="100">{{ score }}
                            </progress>
                            <span class="ml-2"> {{ score }}% </span>
                        </div>
                        <div class="flex flex-wrap lg:w-4/5 sm:mx-auto sm:mb-2 -mx-2">
                            <div class="p-2 sm:w-1/2 w-full">
                                <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                                    <svg fill=" none" stroke="currentColor" stroke-linecap="round"
                                         stroke-linejoin="round" stroke-width="3"
                                         class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4" viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                        <path d="M22 4L12 14.01l-3-3"></path>
                                    </svg>
                                    <span class="title-font font-medium mr-5 text-purple-700">Correct Answers</span>
                                    <span class="title-font font-medium">{{ props.result.correct_answered }}</span>
                                </div>
                            </div>
                            <div class="p-2 sm:w-1/2 w-full">
                                <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                         stroke-linejoin="round" stroke-width="3"
                                         class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4" viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                        <path d="M22 4L12 14.01l-3-3"></path>
                                    </svg>
                                    <span class="title-font font-medium mr-5 text-purple-700">Total Questions</span>
                                    <span class="title-font font-medium">{{ props.result.total_questions }}</span>
                                </div>
                            </div>
                            <div class="p-2 sm:w-1/2 w-full">
                                <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                         stroke-linejoin="round" stroke-width="3"
                                         class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4" viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                        <path d="M22 4L12 14.01l-3-3"></path>
                                    </svg>
                                    <span class="title-font font-medium mr-5 text-purple-700">Percentage Scored</span>
                                    <span class="title-font font-medium">{{ score }}%</span>
                                </div>
                            </div>
                            <div class="p-2 sm:w-1/2 w-full">
                                <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                         stroke-linejoin="round" stroke-width="3"
                                         class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4" viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                        <path d="M22 4L12 14.01l-3-3"></path>
                                    </svg>
                                    <span class="title-font font-medium mr-5 text-purple-700">Time Spent</span><span
                                    class="title-font font-medium">{{ props.result.exam.duration }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap lg:w-4/5 sm:mx-auto sm:mb-2 -mx-2 mt-4">
                            <BaseButtons class="justify-between w-full p-2">
                                <BaseButton
                                    routeName="results.show"
                                    :routeParams="props.result.id"
                                    color="info"
                                    type="button"
                                    label="See Quizzes Details"
                                    class="w-full sm:w-auto mr-0 "
                                />
                                <BaseButton
                                    routeName="results.index"
                                    color="info"
                                    type="button"
                                    label="See All Your Quizzes"
                                    class="w-full sm:w-auto mr-0 "
                                />
                            </BaseButtons>
                        </div>
                    </div>
                </div>
            </section>
        </CardBox>
    </SectionMain>
</template>
