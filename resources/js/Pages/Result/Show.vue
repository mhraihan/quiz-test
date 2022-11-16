
<script setup>

import {Head} from "@inertiajs/inertia-vue3";
import {
    mdiArrowLeft,
} from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";

import SectionMain from "@/Components/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";



const props = defineProps({
    result: Object,
    questions: Object,
});

const quiz = (answer,correct_answer,  key ) => {
    if (answer === key  ) {
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

            <CardBox  class=" overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h1 class="text-sm leading-6 font-medium text-gray-900">
                        Quiz Information
                    </h1>
                    <p class="mt-1 max-w-2xl text-sm text-gray-700">
                        You took this quiz {{ props.result.exam.how_long }} on <span class="text-bold bg-green-300 px-2 rounded-lg"> {{ props.result.exam.day }} </span>
                    </p>
                </div>
                <div class="border-t border-gray-300">
                    <dl>
                        <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-700">
                                Exam Date
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ props.result.exam.date }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-700">
                                Quiz Completion Status
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ props.result.complete ? 'Completed' : 'Not Completed' }}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-700">
                                Total Quiz Questions
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ props.result.total_questions }}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-700">
                                Correct Answered
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ props.result.correct_answered }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-700">
                                Quiz Score
                            </dt>
                            <dd :class=" props.result.score >= 50 ? ' bg-green-300' : 'bg-red-300' " class="mt-1 px-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2  rounded-lg">
                                {{ props.result.score }} %
                            </dd>

                        </div>
                        <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-700">
                                Quiz Duration
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ props.result.exam.duration }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </CardBox>
           <CardBox v-for="(question,key) in  props.questions " :key="key" class="mt-6">
               <div class="px-4 py-5 sm:px-6">
                   <div class="text-lg leading-6 mb-4 font-medium text-gray-900">
                       <h3><span class="mr-2 font-extrabold"> {{ key + 1}}</span> {{ question.title }}</h3>
                       <details class="block text-xs mt-2" v-if="question.explain">
                           <summary class="p-1 hover:cursor-pointer" id="headingOne">
                               <span class="underline text-blue-500 hover:text-blue-700 focus:outline-none text-xs " type="button">
                                   Explanation
                               </span>
                           </summary>
                           <div  class="block p-2 bg-green-100 text-xs leading-5 my-3" v-html="question.explain"></div>
                       </details>
                   </div>
                   <div v-for="(option,key) in question.options"  :class="quiz(question.answer,question.correct_answer,key)" class="mt-1 max-w-auto text-sm px-2 rounded-lg  bg-none ">
                       <span class="mr-2 font-extrabold">{{ key }} </span> {{ option }}
                       <span v-if="key === question.correct_answer" class="p-1 font-extrabold">(Correct Answer)</span>
                       <span v-if="key === question.answer" class="p-1 font-extrabold">(Your Answer)</span>
                   </div>

               </div>
           </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
