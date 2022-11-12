<script setup>
import { reactive } from "vue";
import SectionMain from "@/Components/SectionMain.vue";

import { usePage} from "@inertiajs/inertia-vue3";

const profileForm = reactive({
    name: usePage().props.value.auth.user.name ,
    email: usePage().props.value.auth.user.name,
});

const props = defineProps({
    result: Object
})
</script>

<template>
    <SectionMain>
        <div class="bg-white rounded-lg shadow-lg p-5 md:p-20 mx-2">
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
                                <button class="bg-green-300 px-2 mx-2 hover:green-400 rounded-lg hover:underline" @click="$emit('backToQuiz')">
                                    Take another Quiz
                                </button>
                            </p>
                            <p class="text-md mt-10"> Dear <span class="font-extrabold text-blue-600 mr-2"> {{ profileForm.name }}! </span> Your latest Quiz Result
                                <a class="bg-green-300 px-2 mx-2 hover:green-400 rounded-lg underline" href="http://quizapp.test/appuser/userQuizDetails/14">Show quiz details</a></p>
                            <progress class="text-base leading-relaxed xl:w-2/4 lg:w-3/4 mx-auto"  :value="props.result.score" max="100">{{ props.result.score }} </progress>
                            <span class="ml-2"> {{ props.result.score }}% </span>
                        </div>
                        <div class="flex flex-wrap lg:w-4/5 sm:mx-auto sm:mb-2 -mx-2">
                            <div class="p-2 sm:w-1/2 w-full">
                                <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                                    <svg fill=" none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4" viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                        <path d="M22 4L12 14.01l-3-3"></path>
                                    </svg>
                                    <span class="title-font font-medium mr-5 text-purple-700">Correct Answers</span>
                                    <span class="title-font font-medium">{{ props.result.correct_answered }}</span>
                                </div>
                            </div>
                            <div class="p-2 sm:w-1/2 w-full">
                                <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4" viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                        <path d="M22 4L12 14.01l-3-3"></path>
                                    </svg>
                                    <span class="title-font font-medium mr-5 text-purple-700">Total Questions</span>
                                    <span class="title-font font-medium">{{ props.result.total_questions }}</span>
                                </div>
                            </div>
                            <div class="p-2 sm:w-1/2 w-full">
                                <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4" viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                        <path d="M22 4L12 14.01l-3-3"></path>
                                    </svg>
                                    <span class="title-font font-medium mr-5 text-purple-700">Percentage Scored</span>
                                    <span class="title-font font-medium">{{ props.result.score }}%</span>
                                </div>
                            </div>
                            <div class="p-2 sm:w-1/2 w-full">
                                <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" class="text-indigo-500 w-6 h-6 flex-shrink-0 mr-4" viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                        <path d="M22 4L12 14.01l-3-3"></path>
                                    </svg>
                                    <span class="title-font font-medium mr-5 text-purple-700">Time Spent</span><span class="title-font font-medium">Fail</span>
                                </div>
                            </div>
                        </div>
                        <div class="mx-auto min-w-full p-2 md:flex m-2 justify-between">
                            <a href="http://quizapp.test/appuser/userQuizDetails/14" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">See Quizzes Deatils</a>
                            <a href="http://quizapp.test/appuser/userQuizHome" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">See All Your Quizzes</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </SectionMain>
</template>
