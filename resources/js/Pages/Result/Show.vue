
<script setup>

import {Head} from "@inertiajs/inertia-vue3";
import {
    mdiTableBorder,
} from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";

import SectionMain from "@/Components/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";



const props = defineProps({
    result: Object,
});



</script>

<template>
    <Head title="Result Details"/>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiTableBorder" title="Result Details" main/>

            <CardBox  class=" overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h1 class="text-sm leading-6 font-medium text-gray-900">
                        Quiz Information
                    </h1>
                    <p class="mt-1 max-w-2xl text-sm text-gray-700">

                        You took this quiz {{ props.result.date.how_long }} on <span class="text-bold bg-green-300 px-2 rounded-lg"> {{ props.result.date.day }} </span>
                    </p>
                </div>
                <div class="border-t border-gray-300">
                    <dl>
                        <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-700">
                                Exam Date
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ props.result.date.exam }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-700">
                                Quiz Completion Status
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ props.result.complete ? 'Complete' : 'Incomplete' }}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-700">
                                Total Quiz Questions
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
                                {{ props.result.duration }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </CardBox>
           <CardBox class="mt-6">
               <div class="px-4 py-5 sm:px-6">
                   <h3 class="text-lg leading-6 mb-2 font-medium text-gray-900">
                       <span class="mr-2 font-extrabold"> 1</span> Delectus occaecat o
                       <div class="block text-xs">
                           <div class="p-1" id="headingOne">
                               <button @click="show=!show" class="underline text-blue-500 hover:text-blue-700 focus:outline-none text-xs " type="button">
                                   Explanation
                               </button>
                           </div>
                           <div  class="block p-2 bg-green-100 text-xs" style="display: none;">
                               Asperiores sit pari
                           </div>
                       </div>
                   </h3>
                   <div class="mt-1 max-w-auto text-sm px-2 rounded-lg text-white bg-none bg-green-500">
                       <span class="mr-2 font-extrabold">A </span> Facere est repellend
                   </div>
                   <div class="mt-1 max-w-auto text-sm px-2 rounded-lg text-white bg-none bg-green-500">
                       <span class="mr-2 font-extrabold">B </span> Facilis laboriosam
                   </div>
                   <div class="mt-1 max-w-auto text-sm px-2 rounded-lg text-white bg-none bg-green-500">
                       <span class="mr-2 font-extrabold">C </span> Et quam incididunt a
                   </div>
                   <div class="mt-1 max-w-auto text-sm px-2 rounded-lg text-white bg-none bg-green-500">
                       <span class="mr-2 font-extrabold">D </span> Aperiam alias ducimu
                   </div>
               </div>
           </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
