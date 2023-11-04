<script setup>
import {Head,Link} from "@inertiajs/inertia-vue3";
import CardBox from "@/Components/CardBox.vue";
import {computed, onMounted} from "vue";
import QuestionDetails from "@/Shared/Question/QuestionDetails.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {mdiArrowLeft} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import Overview from "@/Pages/Result/Overview.vue";
import QuestionImage from "@/Shared/Question/QuestionImage.vue";
import {renderMath} from "@/config";

const props = defineProps({
    Categories: Object,
    Topics: Object,
    Question: Object,
    image: String,
    User: String,
});
const questionEnglish = computed(() => {
    return {
        title: props.Question.title,
        details: props.Question.details,
        explain: props.Question.explain,
        options: props.Question.options,
        image: props.image,
    }
})
const questionChinese = computed(() => {
    return {
        title: props.Question.title_two,
        details: props.Question.details_two,
        explain: props.Question.explain_two,
        options: props.Question.options_two,
        image: props.image,

    }
})
const category = computed(() => props.Categories.find(c => props.Question.category_id === c.id));
const topic = computed(() => props.Topics.find(t => props.Question.topic_id === t.id));

onMounted(() => {
    renderMath();
});

</script>

<template>

    <Head title="Question Details"/>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton link="admin.questions.index" :icon="mdiArrowLeft" title="Question Details"
                                        main/>

            <div class="flex flex-wrap items-center justify-between mb-6 sm:flex-row flex-col">
                <BaseButton class="w-full md:w-auto mb-3 md:mb-0" color="info" label="Create a new Question" routeName="admin.questions.create"/>
                <BaseButton class="w-full md:w-auto" color="info" label="Edit Question" routeName="admin.questions.edit"
                            :routeParams="props.Question.id"/>
            </div>
            <CardBox class=" overflow-hidden sm:rounded-lg mb-4">
                <div class="border-t border-gray-300 ">
                    <dl>
                        <Overview label="Category" :value="category.title"/>
                        <Link :href="route('admin.topics.show', props.Question.topic_id)">
                            <Overview label="Topics" class="uppercase" :value="topic.title"/>
                        </Link>
                        <Overview label="Correct Answer" class="uppercase" :value="props.Question.correct_answer"/>
                        <Overview label="Added By" class="uppercase" :value="props.User"/>
                        <Overview label="Added At" class="uppercase" :value="props.Question.created_at"/>
                    </dl>
                </div>
            </CardBox>
            <SectionMain class="!p-0 my-10">
                <h2 class="my-3 font-bold text-2xl uppercase">Question (English)</h2>

                <CardBox class=" overflow-hidden sm:rounded-lg">
                    <QuestionDetails :question="questionEnglish"/>
                </CardBox>
            </SectionMain>
            <SectionMain class="!p-0">
                <h2 class="my-3 font-bold text-2xl uppercase">Question (Chinese)</h2>

                <CardBox class=" overflow-hidden sm:rounded-lg">
                    <QuestionDetails :question="questionChinese"/>
                </CardBox>
            </SectionMain>
            <SectionMain class="!p-0">
                <QuestionImage :image="image" :title="props.Question.title"/>
            </SectionMain>
        </SectionMain>
    </LayoutAuthenticated>
</template>

