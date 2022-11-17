<script setup>

import {Head} from "@inertiajs/inertia-vue3";
import {mdiArrowLeft,} from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";

import SectionMain from "@/Components/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import Overview from "@/Pages/Result/Overview.vue";


const props = defineProps({
    result: Object,
    questions: Object,
});

const quiz = (answer, correct_answer, key) => {
    if (answer === key) {
        return answer !== correct_answer ? 'bg-red-600 text-white' : 'bg-green-500 text-white';
    }
    return key === correct_answer ? 'bg-green-500 text-white' : '';
}


</script>

<template>
    <Head title="Result Details"/>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton link="results.index" :icon="mdiArrowLeft" title="Result Details" main/>

            <CardBox class=" overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h1 class="text-sm leading-6 font-medium text-gray-900">
                        Quiz Information
                    </h1>
                    <p class="mt-1 max-w-2xl text-sm text-gray-700">
                        You took this quiz {{ props.result.exam.how_long }} on <span
                        class="text-bold bg-green-300 px-2 rounded-lg"> {{ props.result.exam.day }} </span>
                    </p>
                </div>
                <div class="border-t border-gray-300">
                    <dl>
                        <Overview label="Exam Date" :value="props.result.exam.date"/>
                        <Overview label="Quiz Completion Status"
                                  :value="props.result.complete ? 'Completed' : 'Not Completed'"/>
                        <Overview label="Total Quiz Questions" :value="props.result.total_questions"/>
                        <Overview label="Correct Answered" :value="props.result.correct_answered"/>
                        <Overview label=" Quiz Score" :value="props.result.score" prefix="%"/>
                        <Overview label="Quiz Duration" :value="props.result.exam.duration"/>
                    </dl>
                </div>
            </CardBox>
            <CardBox v-for="(question,key) in  props.questions " :key="key" class="mt-6">
                <div class="px-4 py-5 sm:px-6">
                    <div class="text-lg leading-6 mb-4 font-medium text-gray-900">
                        <h3><span class="mr-2 font-extrabold"> {{ key + 1 }}</span> {{ question.title }}</h3>
                        <details class="block text-xs mt-2" v-if="question.details">
                            <summary class="p-1 hover:cursor-pointer" id="headingOne">
                               <span class="underline text-blue-500 hover:text-blue-700 focus:outline-none text-xs "
                                     type="button">
                                   Question Details
                               </span>
                            </summary>
                            <div class="block p-2 bg-green-100 text-xs leading-5 my-3" v-html="question.details"></div>


                        </details>
                        <details class="block text-xs mt-2" v-if="question.explain">
                            <summary class="p-1 hover:cursor-pointer" id="headingOne">
                               <span class="underline text-blue-500 hover:text-blue-700 focus:outline-none text-xs "
                                     type="button">
                                   Explanation
                               </span>
                            </summary>
                            <div class="block p-2 bg-green-100 text-xs leading-5 my-3" v-html="question.explain"></div>
                        </details>
                    </div>
                    <div v-for="(option,key) in question.options"
                         :class="quiz(question.answer,question.correct_answer,key)"
                         class="mt-1 max-w-auto text-sm px-2 rounded-lg  bg-none ">
                        <span class="mr-2 font-extrabold">{{ key }} </span> {{ option }}
                        <span v-if="key === question.correct_answer" class="p-1 font-extrabold">(Correct Answer)</span>
                        <span v-if="key === question.answer" class="p-1 font-extrabold">(Your Answer)</span>
                    </div>

                </div>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
