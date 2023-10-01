<script setup>

import {Head} from "@inertiajs/inertia-vue3";
import {mdiArrowLeft,} from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";

import SectionMain from "@/Components/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import Overview from "@/Pages/Result/Overview.vue";
import {computed, onMounted} from "vue";
import {languages, renderMath} from "@/config";
import QuestionDetails from "@/Shared/Question/QuestionDetails.vue";
import QuestionImage from "@/Shared/Question/QuestionImage.vue";


const props = defineProps({
    result: Object,
    questions: Object,
    name: {
        type: String,
        required: true,
    }
});

const language = computed(() => languages.find(language => language.value === props.result.language)?.label)
onMounted(() => {
    renderMath();
});
</script>

<template>
    <Head title="Result Details"/>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton link="results.index" :icon="mdiArrowLeft" title="Result Details" main/>

            <CardBox class=" overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h1 class="text-sm leading-6 font-medium text-gray-900">
                        Quiz Information <span>({{ props.name }})</span>
                    </h1>
                    <p class="mt-1 max-w-2xl text-sm text-gray-700">
                        You took this quiz {{ props.result.exam.how_long }} on <span
                        class="text-bold bg-green-300 px-2 rounded-lg"> {{ props.result.exam.day }} </span>
                    </p>
                </div>
                <div class="border-t border-gray-300">
                    <dl>
                        <Overview label="Exam Language" :value="language"/>
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
                <QuestionDetails :question="question" key="key"/>
                <QuestionImage :image="question.image" :title="question.title"/>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
