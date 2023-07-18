<style src="@vueform/multiselect/themes/default.css"></style>
<script setup>
import Multiselect from '@vueform/multiselect'

import {Head, useForm} from "@inertiajs/inertia-vue3";
import {
    mdiTableBorder,
} from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";

import SectionMain from "@/Components/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import FormField from "@/Components/FormField.vue";
import {notify} from "notiwind"
import intus from "intus";
import {isBetween, isRequired} from "intus/rules";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import axios from "axios";
import {computed, ref} from "vue";
import Loader from "@/Components/Loader.vue";
import Question from "@/Pages/Quiz/Question.vue";
import Summary from "@/Pages/Quiz/Summary.vue";
import {range} from "lodash";

const props = defineProps({
    Categories: Object,
    Topics: Object,
});


const topics = props.Topics.length > 1 ? [{label: 'Random', value: 0}, ...props.Topics] : props.Topics;

const quiz = useForm({
    category_id: props?.Categories[0]?.value || null,
    topic_id: [topics[0].value],
    howManyQuestions: 1,
    questions: null,
    result: null,
    start_time: null,
});
const title = computed(() => quiz.result ? 'Quiz Summary' : 'Get your Quiz');
const backToQuiz = () => {
    quiz.questions = null;
    quiz.result = null;
    quiz.category_id = props?.Categories[0]?.value || null;
    quiz.topic_id = [topics[0].value];
    quiz.howManyQuestions = 1;
}
const loading = ref(false);
const howManyQuestions = computed(() => range(1, 21));
const notification = ({
                          type = 'error',
                          title = 'Error',
                          text = 'Please select Question Topics, Difficulty label and Question Interval'
                      } = {}) => {
    notify({
        group: "notification",
        type,
        title,
        text,
    }, 4000) // 4s
}
const showLoader = () => {
    loading.value = !loading.value;
}

const getQuiz = () => {
    quiz.clearErrors();
    const validation = intus.validate(quiz.data(), {
        "topic_id": [isRequired()],
        "category_id": [isRequired(), isBetween(1, 4)],
        "howManyQuestions": [isRequired(), isBetween(1, 20)]
    }, {
        'topic_id.isRequired': 'Please select the questions Topics',
        'category_id.isRequired': 'Please select the questions Category',
        'howManyQuestions.isRequired': 'Please select the number of questions',
    });
    if (validation.passes()) {
        showLoader();
        axios.post(route("quiz.show"), {
            topic_id: quiz.topic_id,
            category_id: quiz.category_id,
            howManyQuestions: quiz.howManyQuestions,
        }).then(res => {
            quiz.questions = res.data.questions;
            quiz.start_time = res.data.start_time;
            if (!quiz.questions.length) {
                notification({text: 'No Question is available'});
            }
        }).catch(() => {
            location.reload();
            notification({'text':'Session is expired'});
        }).finally(() => showLoader());
    } else {
        notification();
        quiz.setError(validation.errors());
    }
}
const submit = (questions) => {
    showLoader();
    if (!questions.questions_answered.length){
        notification({text:  'Exam Postponed'});
        showLoader();
        backToQuiz();
        return;
    }
    axios.post(route("results.store"), questions).then(res => {
        quiz.result = res.data.result;
        if (quiz.result) {
            notification({
                type: 'success',
                title: 'Success',
                text: 'Here is your last exam Summary',
            });
        }
    }).catch((e) => {
        notification({text: e.response?.data?.message || 'Something went wrong'});
    }).finally(() => showLoader());
}
</script>

<template>
    <Head :title="title"/>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiTableBorder" :title="title" main/>
            <Loader v-if="loading" class="mt-48"/>
            <CardBox v-if="!loading && !quiz.questions && !quiz.result" @submit.prevent="getQuiz" is-form
                     class="max-w-2xl mx-auto">
                <FormField
                    label="Choose one or more Topics"
                    help="Required. Please select the questions Topics"
                    :error="quiz.errors.topic_id"
                >
                    <Multiselect
                        class="border-red-500"
                        mode="tags"
                        name="topic"
                        :close-on-select="false"
                        v-model="quiz.topic_id"
                        placeholder="Choose one or more Topics"
                        :options="topics"></Multiselect>

                </FormField>
                <FormField
                    label="Choose a difficulty"
                    help="Required. Please select the questions category"
                    :error="quiz.errors.category_id"

                >
                    <Multiselect
                        :uppercase="'capitalize'"
                        v-model="quiz.category_id"
                        name="category"
                        type="select"
                        placeholder="Choose a category"

                        :options="props.Categories"
                    />
                </FormField>
                <FormField
                    label="How many questions do you want to take?"
                    help="Required. Please select the number of questions"
                    :error="quiz.errors.howManyQuestions"
                >
                    <Multiselect
                        :uppercase="'capitalize'"
                        v-model="quiz.howManyQuestions"
                        name="howManyQuestions"
                        type="select"
                        placeholder="Please select the number of questions"
                        :options="howManyQuestions"
                    />
                </FormField>
                <template #footer>
                    <div class="flex">
                        <BaseButtons class="block w-full">
                            <BaseButton
                                color="info"
                                type="submit"
                                label="Start Quiz"
                                class="block w-full"
                                :disabled="quiz.processing"
                            />
                        </BaseButtons>
                    </div>
                </template>
            </CardBox>
            <Question v-if="!loading && quiz.questions && !quiz.result" :questions="quiz.questions"
                      :start_time="quiz.start_time"
                      @backToQuiz="backToQuiz" @submit="submit"/>
            <Summary v-if="!loading && quiz.result" :result="quiz.result"
                     @backToQuiz="backToQuiz"/>
        </SectionMain>
    </LayoutAuthenticated>
</template>
